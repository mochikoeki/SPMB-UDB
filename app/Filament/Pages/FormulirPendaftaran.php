<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Pages\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Formulir;
use App\Models\Periode;

class FormulirPendaftaran extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.formulir-pendaftaran';
    protected function getFormModel(): string
    {
        return Formulir::class;
    }
    //deklarasi atribut class (sesuaikan dengan name field di form)
    public $no_daftar;
    public $id_user;
    public $id_periode;
    public $nama;
    public $jenis_kelamin;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;
    public $telp;
    public $program_studi;
    //tambahkan hak akses hanya untuk role 'Pendaftar'
    public static function canAccess(): bool
    {
        return Auth::user()->hasRole('Pendaftar');
    }

    public function mount(): void
    {
        //tolak akses halaman jika user tidak memiliki role 'Pendaftar'
        abort_unless(
            Auth::user()->hasRole('Pendaftar'),
            403
        );
        $periode = Periode::where("aktif", 1)->first();
        //jika ada periode aktif
        if ($periode) {
            //ambil data formulir dari periode dan user yang login
            $dataFormulir = Formulir::where("id_periode", $periode->id)
                ->where("id_user", Auth::user()->id)
                ->first();
            //jika tidak ada data formulir, maka buat data awal periode dan nama pendaftar
            if (!$dataFormulir) {
                $data_awal = [
                    'id_periode' => $periode->id,
                    'nama' => Auth::user()->name

                ];
            } else { //jika data sudah ada
                $data_awal = $dataFormulir->toArray(); //data awal dari database
            }
            //data awal dimasukkan ke form
            $this->form->fill($data_awal);
        }
    }

    public function no_daftar_baru()
    {
        //ambil data periode aktif
        $periode = Periode::where("aktif", 1)->first();
        //ambil data nomor_daftar terakhir
        $pendaftar = Formulir::select(DB::raw('right(no_daftar,4) as terakhir'))
            ->where("id_periode", $periode->id)
            ->orderBy("no_daftar", "desc")
            ->first();
        //jika data pendaftar terakhir ada
        if ($pendaftar) {
            $urut_baru = $pendaftar->terakhir + 1; //tambah 1 dari angka sebelumnya
        } else {
            $urut_baru = 1; //pendaftar pertama langsung diisi 1
        }
        //buat format 0001
        $urut_baru = str_pad($urut_baru, 4, '0', STR_PAD_LEFT);
        //gabungkan tahun dgn urut_baru
        $no_daftar = $periode->id . $urut_baru;
        return $no_daftar;
    }

    public function simpan(): void
    {
        //ambil data di form
        $dataForm = $this->form->getState();
        //jika no_daftar kosong, maka: buat no_daftar_baru dan create data
        if (!isset($dataForm['no_daftar'])) {
            $dataForm['no_daftar'] = $this->no_daftar_baru();
            $dataForm['id_user'] = Auth::user()->id;
            Formulir::create($dataForm);
            $this->form->fill($dataForm); //agar no_daftar terupdate di form
        } else {
            //jika sudah ada no_daftarnya berarti update data
            Formulir::where("no_daftar", $dataForm['no_daftar'])->update($dataForm);
        }
        //tampilan notifikasi pesan
        Notification::make()
            ->title('Berhasil Simpan')
            ->success()
            ->send();
    }

    protected function getFormSchema(): array
    {
        return [

            Forms\Components\TextInput::make('id_periode')
                ->label('Periode')

                ->placeholder('- Belum Ada Periode Aktif - ')

                ->readonly()
                ->required(),

            Forms\Components\TextInput::make('no_daftar')
                ->label('No Daftar')

                ->placeholder('- Otomatis - ')

                ->disabled(),
            Forms\Components\TextInput::make('nama')
                ->label('Nama Lengkap')

                ->required(),

            Forms\Components\Radio::make('jenis_kelamin')
                ->label('Jenis Kelamin')

                ->options([

                    'L' => 'Laki-laki',
                    'P' => 'Perempuan',

                ])->required(),
            Forms\Components\TextInput::make('tempat_lahir')
                ->label('Tempat Lahir')

                ->required(),

            Forms\Components\DatePicker::make('tanggal_lahir')
                ->label('Tanggal Lahir')
                ->displayFormat('d/m/Y')
                ->required(),

            Forms\Components\TextArea::make('alamat')
                ->label('Alamat Lengkap')

                ->required(),

            Forms\Components\TextInput::make('telp')
                ->label('Telp/WA')

                ->tel()
                ->required(),

            Forms\Components\Select::make('program_studi')
                ->label('Pilihan Program Studi')

                ->relationship("programStudi", "kode_prodi")

                ->getOptionLabelFromRecordUsing(function (Model $record) {

                    return $record->jenjang->nama_jenj_didik . " - " . $record->nama_prodi;
                })
                ->preload()
                ->required(),
        ];
    }
}
