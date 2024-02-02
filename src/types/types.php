<?php

declare(strict_types=1);

class ConnectResponse
{
    public string $Estado;
    public int $expires_in;
    public string $firmware_version;
    public int $id;
    public string $jwt;
    public string $rif;
    public string $sn;
    public string $token_type;

    public function __construct(
        string $Estado,
        int $expires_in,
        string $firmware_version,
        int $id,
        string $jwt,
        string $rif,
        string $sn,
        string $token_type
    ) {
        $this->Estado = $Estado;
        $this->expires_in = $expires_in;
        $this->firmware_version = $firmware_version;
        $this->id = $id;
        $this->jwt = $jwt;
        $this->rif = $rif;
        $this->sn = $sn;
        $this->token_type = $token_type;
    }
}


// base class for all elements that can be altered
class Manipulable
{
    public int $borrado;
    public string|int $fechaCre = "";
    public string|int $fechaMo = "";
    public int $id;
}

// this type is only present in classes that inherit the person class
class DatosPersonales
{
    public string $Celular;
    public string $Direccion;
    public string $Email;
    public string $Facebook;
    public string $Instagram;
    public string $PaginaWeb;
    public string $Telefono;
    public string $Twitter;

    public function __construct(
        string $celular = "",
        string $direccion = "",
        string $email = "",
        string $facebook = "",
        string $instagram = "",
        string $paginaWeb = "",
        string $telefono = "",
        string $twitter = ""
    ) {
        $this->Celular = $celular;
        $this->Direccion = $direccion;
        $this->Email = $email;
        $this->Facebook = $facebook;
        $this->Instagram = $instagram;
        $this->PaginaWeb = $paginaWeb;
        $this->Telefono = $telefono;
        $this->Twitter = $twitter;
    }
}

class Persona extends Manipulable
{
    public DatosPersonales $datosPersonales;
    public string $nombre;
    public string $rif;
}

class Permisos
{
    public bool $Anulacion;
    public bool $Correcion;
    public bool $CorreccionPago;
    public bool $CrearProducto;
    public bool $Descuentos;
    public bool $FondoCaja;
    public bool $ModificarFactura;
    public bool $Recargos;
    public bool $ReportesZ;
    public bool $RetiroCaja;

    public function __construct(
        bool $anulacion,
        bool $correcion,
        bool $correccionPago,
        bool $crearProducto,
        bool $descuentos,
        bool $fondoCaja,
        bool $modificarFactura,
        bool $recargos,
        bool $reportesZ,
        bool $retiroCaja
    ) {
        $this->Anulacion = $anulacion;
        $this->Correcion = $correcion;
        $this->CorreccionPago = $correccionPago;
        $this->CrearProducto = $crearProducto;
        $this->Descuentos = $descuentos;
        $this->FondoCaja = $fondoCaja;
        $this->ModificarFactura = $modificarFactura;
        $this->Recargos = $recargos;
        $this->ReportesZ = $reportesZ;
        $this->RetiroCaja = $retiroCaja;
    }
}

class Usuario extends Persona
{
    public string $codigo;
    public Permisos $permisos;
    public string $rsocial;
    public int $tipo;

    public function __construct(
        int $borrado = 0,
        int $id,
        DatosPersonales $datosPersonales,
        string $nombre,
        string $rif,
        string|int $fechaCre,
        string|int $fechaMo,
        Permisos $permissions,
        string $rsocial,
        int $tipo,
        string|null $codigo = "",
    ) {
        $borrado > 1 || $borrado < 0 ? $this->borrado = 0 : $this->borrado = $borrado;
        $this->id = $id;
        $this->datosPersonales = $datosPersonales;
        $this->nombre = $nombre;
        $this->fechaCre = $fechaCre;
        $this->fechaMo = $fechaMo;
        $this->permisos = $permissions;
        $this->rif = $rif;
        $this->rsocial = $rsocial;
        $tipo > 3 || $tipo < 0 ? $this->tipo = 0 : $this->tipo = $tipo;
        is_null($codigo) ? $this->codigo = "" : $this->codigo = $codigo;
    }
}

class Cliente extends Persona
{
    public string $codigo = "";
    public int $fechaRe;
    public int $fechaUl;
    public int $id;

    public function __construct(
        string $codigo = "",
        int $fechaRe,
        int $fechaUl,
        int $id,
        string $rif = "",
        string $nombre = "",
        DatosPersonales $datosPersonales
    ) {
        $this->codigo = $codigo;
        $this->fechaRe = $fechaRe;
        $this->fechaUl = $fechaUl;
        $this->id = $id;
        $this->rif = $rif;
        $this->nombre = $nombre;
        $this->datosPersonales = $datosPersonales;
    }
}