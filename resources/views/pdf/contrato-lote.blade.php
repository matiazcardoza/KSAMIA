<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrato de Compra-Venta de Lote</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .info-label {
            font-weight: bold;
            background-color: #f8f9fa;
            width: 30%;
        }
        .info-value {
            background-color: #ffffff;
        }
        .clausulas {
            text-align: justify;
            margin-bottom: 15px;
        }
        .clausula {
            margin-bottom: 15px;
        }
        .clausula-titulo {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .firmas {
            margin-top: 50px;
            width: 100%;
            display: table;
        }
        .firma {
            display: table-cell;
            text-align: center;
            width: 50%;
            padding: 20px;
        }
        .linea-firma {
            border-top: 1px solid #333;
            margin-top: 60px;
            padding-top: 5px;
        }
        .fecha {
            text-align: right;
            margin-bottom: 20px;
            font-style: italic;
        }
        .precio-destacado {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">CONTRATO DE COMPRA-VENTA DE LOTE</div>
        <div class="subtitle">{{ $proyecto['nombre'] }}</div>
    </div>

    <div class="fecha">
        Fecha: no hay
    </div>

    <div class="section">
        <div class="section-title">INFORMACIÓN DEL PROYECTO</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Proyecto:</td>
                <td class="info-value">{{ $proyecto['nombre'] }}</td>
            </tr>
            <tr>
                <td class="info-label">Ubicación:</td>
                <td class="info-value">{{ $proyecto['ubicacion'] }}</td>
            </tr>
            <tr>
                <td class="info-label">Descripción:</td>
                <td class="info-value">{{ $proyecto['descripcion'] }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">INFORMACIÓN DEL LOTE</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Manzana:</td>
                <td class="info-value">{{ $manzana['nombre'] }}</td>
            </tr>
            <tr>
                <td class="info-label">Lote N°:</td>
                <td class="info-value">{{ $lote['numero'] }}</td>
            </tr>
            <tr>
                <td class="info-label">Área:</td>
                <td class="info-value">{{ $lote['area'] }} m²</td>
            </tr>
            <tr>
                <td class="info-label">Precio:</td>
                <td class="info-value precio-destacado">S/. no hay</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">INFORMACIÓN DEL CLIENTE</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Cliente:</td>
                <td class="info-value">{{ $cliente->nombre }}</td>
            </tr>
            <tr>
                <td class="info-label">Documento:</td>
                <td class="info-value">{{ $cliente->documento }}</td>
            </tr>
            <tr>
                <td class="info-label">Teléfono:</td>
                <td class="info-value">{{ $cliente->telefono }}</td>
            </tr>
            <tr>
                <td class="info-label">Email:</td>
                <td class="info-value">{{ $cliente->email }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">TÉRMINOS Y CONDICIONES</div>
        <div class="clausulas">
            <div class="clausula">
                <div class="clausula-titulo">PRIMERA.- OBJETO DEL CONTRATO:</div>
                <div>Por medio del presente contrato, LA EMPRESA se compromete a vender y EL CLIENTE se compromete a comprar el lote N° {{ $lote['numero'] }} ubicado en la Manzana {{ $manzana['nombre'] }} del proyecto {{ $proyecto['nombre'] }}, con un área de {{ $lote['area'] }} m².</div>
            </div>
            
            <div class="clausula">
                <div class="clausula-titulo">SEGUNDA.- PRECIO Y FORMA DE PAGO:</div>
                <div>El precio total del lote es de S/. no hay , el cual será pagado según los términos acordados entre las partes.</div>
            </div>
            
            <div class="clausula">
                <div class="clausula-titulo">TERCERA.- ENTREGA:</div>
                <div>LA EMPRESA se compromete a entregar el lote en las condiciones acordadas y según los plazos establecidos en el cronograma del proyecto {{ $proyecto['nombre'] }}.</div>
            </div>
            
            <div class="clausula">
                <div class="clausula-titulo">CUARTA.- OBLIGACIONES:</div>
                <div>Ambas partes se comprometen a cumplir con todas las obligaciones establecidas en el presente contrato y la normativa vigente aplicable a la compra-venta de inmuebles.</div>
            </div>

            <div class="clausula">
                <div class="clausula-titulo">QUINTA.- JURISDICCIÓN:</div>
                <div>Las partes se someten a la jurisdicción de los tribunales competentes para resolver cualquier controversia que pueda surgir de la interpretación o ejecución del presente contrato.</div>
            </div>
        </div>
    </div>

    <div class="firmas">
        <div class="firma">
            <div class="linea-firma">
                <strong>LA EMPRESA</strong><br>
                Representante Legal<br>
                {{ $proyecto['nombre'] }}
            </div>
        </div>
        <div class="firma">
            <div class="linea-firma">
                <strong>EL CLIENTE</strong><br>
                {{ $cliente->nombre }}<br>
                {{ $cliente->documento }}
            </div>
        </div>
    </div>

    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
        <p>Documento generado automáticamente el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>