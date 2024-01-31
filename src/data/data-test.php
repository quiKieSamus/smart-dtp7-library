<?php

$CONNECT_RESPONSE_SAMPLE = '{
    "Estado": "Ok",
    "expires_in": 7199,
    "firmware_version": "1.1.230617",
    "id": 3,
    "jwt": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJJZCI6MywiVmVyc2lvblBhaXMiOjEsImFwaSI6IjEuMCIsImlhdCI6MTcwNjI4MzI4NC40MDU0MjJ9.me_V8acGSqaeVplfpT1o15BK-1SCV0mF4E7-8oTV1xM",
    "rif": "????????????",
    "sn": "X9B0000688",
    "token_type": "bearer"
  }';

$WRONG_CONNECT_RESPONSE_SAMPLE = '{
    "stado": "Ok",
    "expires_in": 7199,
    "firmware_version": "1.1.230617",
    "id": 3,
    "jwt": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJJZCI6MywiVmVyc2lvblBhaXMiOjEsImFwaSI6IjEuMCIsImlhdCI6MTcwNjI4MzI4NC40MDU0MjJ9.me_V8acGSqaeVplfpT1o15BK-1SCV0mF4E7-8oTV1xM",
    "rif": "????????????",
    "sn": "X9B0000688",
    "token_type": "bearer"
  }';


$USUARIO_RESPONSE_GET_SAMPLE = '[
  {
    "borrado": 0,
    "datosPersonales": {},
    "fechaCre": 1687200362,
    "fechaMo": 1687200362,
    "id": 1,
    "nombre": "Gerente",
    "permisos": {
      "Anulacion": true,
      "Correccion": true,
      "CorreccionPago": true,
      "CrearProducto": true,
      "Descuentos": true,
      "FondoCaja": true,
      "ModificarFactura": true,
      "Recargos": true,
      "ReportesZ": true,
      "RetiroCaja": true
    },
    "rsocial": "Gerente",
    "tipo": 3
  },
  {
    "borrado": 0,
    "codigo": "123456789",
    "datosPersonales": {},
    "fechaCre": 1687200362,
    "fechaMo": 1687200362,
    "id": 2,
    "nombre": "Cajero",
    "permisos": {
      "Anulacion": true,
      "Correccion": true,
      "CorreccionPago": true,
      "CrearProducto": true,
      "Descuentos": true,
      "FondoCaja": true,
      "ModificarFactura": true,
      "Recargos": true,
      "ReportesZ": true,
      "RetiroCaja": true
    },
    "rif": "123456789",
    "rsocial": "Cajero",
    "tipo": 0
  },
  {
    "borrado": 0,
    "codigo": "",
    "datosPersonales": {},
    "fechaCre": 1704983047,
    "fechaMo": 1704983047,
    "id": 3,
    "nombre": "genionet",
    "permisos": {
      "Anulacion": false,
      "Correccion": false,
      "CorreccionPago": false,
      "CrearProducto": false,
      "Descuentos": false,
      "FondoCaja": false,
      "ModificarFactura": false,
      "Recargos": false,
      "ReportesZ": false,
      "RetiroCaja": false
    },
    "rif": "J316297063",
    "rsocial": "Genionet",
    "tipo": 2
  },
  {
    "borrado": 0,
    "codigo": "PR00099",
    "datosPersonales": {},
    "fechaCre": 1704988377,
    "fechaMo": 1704988377,
    "id": 4,
    "nombre": "Carro Ultra Arrecho",
    "permisos": {
      "Anulacion": false,
      "Correccion": false,
      "CorreccionPago": false,
      "CrearProducto": false,
      "Descuentos": false,
      "FondoCaja": false,
      "ModificarFactura": false,
      "Recargos": false,
      "ReportesZ": false,
      "RetiroCaja": false
    },
    "rif": "",
    "rsocial": "",
    "tipo": 0
  }
]';