<?php

namespace App\Livewire\Proyectos;

use Livewire\Component;
use App\Models\Proyecto;
use Flux\Flux;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use ZipArchive;

class Proyectos extends Component
{
    public $proyectos, $id_proyecto;
    public function mount(){
        $this->proyectos=Proyecto::orderBy('id_proyecto', 'desc')->get();
    }
    public function render()
    {
        return view('livewire.proyectos.proyectos');
    }

    #[On("reloadProyectos")]
    public function reloadProyectos(){
        $this->proyectos=Proyecto::orderBy('id_proyecto', 'desc')->get();
    }

    public function editar($id_proyecto){
        $this->dispatch("EditarProyecto", $id_proyecto);
    }


    public function eliminar($id_proyecto){
        $this->id_proyecto=$id_proyecto;
        Flux::modal("eliminar-proyecto")->show();
    }

    public function destroy(){
        $proyecto = Proyecto::find($this->id_proyecto);
        $carpetaProyecto = $this->sanitizarNombreCarpeta($proyecto->nom_proyecto);
        if (Storage::disk('public')->exists($carpetaProyecto)) {
            Storage::disk('public')->deleteDirectory($carpetaProyecto);
        }
        Proyecto::find($this->id_proyecto)->delete();
        $this->reloadProyectos();
        Flux::modal("eliminar-proyecto")->close();
    }

    private function sanitizarNombreCarpeta($nombre)
    {
        $nombre = preg_replace('/[^a-zA-Z0-9\s]/', '', $nombre);
        $nombre = preg_replace('/\s+/', '_', trim($nombre));
        $nombre = strtolower($nombre);
        $nombre = substr($nombre, 0, 50);
        return $nombre;
    }

    public function descargarEvidencias($id_proyecto)
    {
        $proyecto = Proyecto::find($id_proyecto);

        $carpetaProyecto = $this->sanitizarNombreCarpeta($proyecto->nom_proyecto);
        $carpetaEvidencias = $carpetaProyecto . '/evidencias';
        $archivos = Storage::disk('public')->allFiles($carpetaEvidencias);
        $nombreZip = $this->sanitizarNombreCarpeta($proyecto->nom_proyecto) . '_evidencias_' . date('Y-m-d_H-i-s') . '.zip';
        $rutaZip = storage_path('app/temp/' . $nombreZip);
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive;

        if ($zip->open($rutaZip, ZipArchive::CREATE) === TRUE) {
            foreach ($archivos as $archivo) {
                $rutaCompleta = storage_path('app/public/' . $archivo);
                $nombreArchivo = basename($archivo);
                $zip->addFile($rutaCompleta, $nombreArchivo);
            }
            $zip->close();
            return Response::download($rutaZip, $nombreZip)->deleteFileAfterSend(true);
        } else {
            Flux::toast(variant: 'danger', text: 'Error al crear el archivo ZIP');
            return;
        }
    }
}
