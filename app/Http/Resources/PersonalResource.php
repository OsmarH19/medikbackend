<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'PersonalID' => $this->PersonalID,
            'NombrePersonal' => $this->NombrePersonal,
            'ApellidoPaterno' => $this->ApellidoPaterno,
            'ApellidoMaterno' => $this->ApellidoMaterno,
            'SexoID' => $this->SexoID,
            'Telefono' => $this->Telefono,
            'Celular' => $this->Celular,
            'Correo' => $this->Correo,
            'DNI' => $this->DNI,
            'CarnetExtranjeria' => $this->CarnetExtranjeria,
            'Nacionalidad' => $this->Nacionalidad,
            'EmpresaID' => $this->EmpresaID,
            'Estado' => $this->Estado,
            'CorreoEmpresa' => $this->CorreoEmpresa,
            'photo' => $this->photo,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'tipoUsuario' => $this->tipoUsuario,
            'centroMedicoID' => $this->centroMedicoID,
            'rne' => $this->rne,
            'cmp' => $this->cmp,
        ];
    }
}
