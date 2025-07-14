<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Contrato PDF</title>
    <style>
        body { 
            font-family: Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('img/KSAMIA.png');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.15;
            z-index: -1;
        }
        .signatures {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            text-align: center;
        }
        .signature-line {
            border-bottom: 2px solid #000;
            margin: 20px 0 5px 0;
            height: 40px;
        }
    </style>
</head>
<body style="font-size: 11;">
    <div class="container">
        <p style="text-align: center;"><strong>CONTRATO DE ACLARATORIA DE PRECIO</strong></p>
        
        <div style="text-align: justify">
            <p>CONTRATO PRIVADO DE COMPRAVENTA QUE CELEBRAN DE UNA PARTE: <strong>GRUPO KSAMIA INVERSIONES E INMOBILIARIA S.A.C</strong>,
                CON <strong>RUC Nº 20609862964</strong>, REPRESENTADO POR SU GERENTE GENERAL, <strong>ING. GIULIANA INGRID POLO LEONARDO</strong>,
                IDENTIFICADO CON <strong>DNI: 45441393</strong>; <strong>GRUPO WASSI INVERSIONES INMOBILIARIAS S.A.C</strong>, CON <strong>RUC N° 20608721283</strong>,
                REPRESENTADO POR SU GERENTE GENERAL, <strong>ARQ. JESUS RAUL CCALLO BELIZARIO</strong>, IDENTIFICADO CON <strong>DNI: 71738398</strong> Y
                <strong>GRUPO TITOS INVERSIONES E INMOBILIARIA S.A.C</strong>, CON <strong>RUC N° 20614071860</strong>, REPRESENTADO POR SU GERENTE GENERAL, 
                <strong>ABG. KEVIN MOISES TITO LEONARDO</strong>, IDENTIFICADO CON <strong>DNI: 72963696</strong> QUIEN EN ADELANTE SE LES DENOMINARÁ, <strong>"LOS VENDEDORES"</strong>; 
            </p>
            <p>
                Y, DE LA OTRA PARTE: <strong>DON {{$cliente['nombre']}} {{$cliente['apellido']}}</strong>, CON<strong> DNI: {{$cliente['dni']}}</strong>, CON <strong>DOMICILIO EN {{$cliente['direccion']}}</strong>,
                DEL DISTRITO DE AZANGARO, PROVINCIA DE AZANGARO Y DEPARTAMENTO DE PUNO; A QUIEN EN ADELANTE SE LE DENOMINARÁ <strong>"EL COMPRADOR"</strong>,
                MISMO CONTRATO QUE SUJETAN A LO CONTENIDO EN LAS SIGUIENTES CLÁUSULAS.
            </p>
        </div>

        <p style="text-align: center;"><strong>DECLARACIONES</strong></p>
        <div style="text-align: justify">
            <p>
                <strong><u>PRIMERO:</u></strong> EL VENDEDOR ES PROPIETARIO Y ACTUAL POSEEDORES DE UN BIEN INMUEBLE DENOMINADO:<strong>
                "PREDIO RURAL DENOMINADO LLANTA PAMPA SECTOR LLANTA PAMPA PAUCARCOLLA"</strong> UBICADO EN EL DISTRITO DE PAUCARCOLLA,
                PROVINCIA Y DEPARTAMENTO DE PUNO; CUENTA CON UN ÁREA TOTAL DE <strong>4.014800 HAS (CUATRO HECTÁREAS PUNTO CERO CATORCE MIL OCHOCIENTOS METROS CUADRADOS)</strong>,
                <strong>LOS VENDEDORES</strong> DESMEMBRAN E INDEPENDIZAN TRES LOTES DE TERRENO DEL INMUEBLE ALUDIDO Y OTORGAN EN VENTA A FAVOR DEL <strong>COMPRADOR</strong>,
                PARTE DEL PREDIO EN EL SECTOR DE LLANTA PAMPA PAUCARCOLLA, DEL DISTRITO DE PAUCARCOLLA, PROVINCIA Y PROVINCIA DE PUNO.

                <ul>
                    <li>
                        <strong>MANZANA "{{$manzana['nombre']}}" LOTE {{$lote['numero']}} CON UN ÁREA TOTAL DE SETECIENTOS SESENTA METROS CUADRADOS ({{$lote['area']}}) M2.</strong>
                    </li>
                </ul>
            </p>
            <p>
                <strong><u>SEGUNDO:</u></strong> EN CONSIDERACIÓN A LOS ANTECEDENTES ANTES EXPUESTOS, POR EL PRESENTE CONTRATO PRIVADO AMBAS PARTES SE OBLIGAN RECÍPROCAMENTE A
                CELEBRAR EL CONTRATO DE COMPRA VENTA, EN VIRTUD DEL CUAL EL VENDEDOR TRANSFIERE A FAVOR DEL COMPRADOR LA PROPIEDAD DEL INMUEBLE A QUE SE REFIERE LA PRIMERA
                CLÁUSULA DEL PRESENTE CONTRATO EN FORMA DEFINITIVA.
            </p>
            <p>
                <strong><u>TERCERO:</u></strong> EL BIEN INMUEBLE HA SIDO SOMETIDO A LOTIZACIÓN, BAJO LA DENOMINACIÓN DE: <strong>"{{$proyecto['nombre']}}"</strong>.
            </p>
            <p>
                <strong><u>CUARTO:</u></strong> DECLARA EL "COMPRADOR" CONTAR CON LA CAPACIDAD SUFICIENTE PARA LA REALIZACIÓN DEL ACTO JURÍDICO CONSIGNADO EN EL PRESENTE
                DOCUMENTO, CONOCER EL INMUEBLE MATERIA DEL PRESENTE CONTRATO, POR LO QUE MANIFIESTAN SU CONFORMIDAD CON EL OTORGAMIENTO DE LO MISMO.
            </p>
            <p>
                <strong><u>QUINTO:</u></strong> EL PRECIO ESTABLECIDO POR EL INMUEBLE MATERIA DEL PRESENTE CONTRATO Y QUE DEBE PAGO <strong>"EL COMPRADOR"</strong>
                A <strong>"LOS VENDEDORES"</strong> ES DE <strong>S/ 55,000.00</strong> (CINCUENTA Y CINCO MIL CON 00/100 SOLES), MISMO QUE SE CUBRE DE LA SIGUIENTE MANERA:
                <p>
                    <strong>1.-</strong> MEDIANTE DEPÓSITO A LA CUENTA DE LA EMPRESA <strong>GRUPO KSAMIA INVERSIONES E INMOBILIARIA S.A.C</strong> LA CANTIDAD DE
                    <strong>S/20,075.00</strong> (VEINTE MIL SETENTA Y CINCO CON 00/100); <strong>GRUPO WASSI INVERSIONES INMOBILIARIAS S.A.C</strong>, LA CANTIDAD DE
                    <strong>S/4,125.00</strong> (CUATRO MIL CIENTO VEINTI CINCO CON 00/100) Y <strong>GRUPO TITOS INVERSIONES E INMOBILIARIA S.A.C</strong> LA CANTIDAD DE
                    <strong>S/3,300.00</strong> (TRES MIL TRESCIENTOS CON 00/100).
                </p>
                <p>
                    <strong>2.-</strong> CANCELACIÓN EN <strong>EFECTIVO</strong> LA CANTIDAD DE <strong>S/27,500.00</strong> (VEINTI SIETE MIL QUINIENTOS CON 00/100 SOLES).
                </p>
            </p>
            <p>
                <strong><u>SEXTO:</u></strong><strong> "LA PARTE VENDEDORA"</strong> ENTREGA LA POSESIÓN DEL INMUEBLE MATERIA DEL PRESENTE CONTRATO A LA PARTE COMPRADORA
                AL MOMENTO DE CANCELAR EL PRECIO DEL LOTE Y FIRMADO LA ESCRITURA PÚBLICA.
            </p>
            <p>
                <strong><u>SÉPTIMO:</u></strong> LAS PARTES MANIFIESTAN QUE EN LA CELEBRACIÓN DEL PRESENTE CONTRATO NO EXISTE DOLO, ERROR, LESIÓN, MALA FÉ, COACCIÓN,
                O LA EXISTENCIA DE ALGÚN VICIO EN EL CONSENTIMIENTO QUE EN ESTE ACTO OTORGAN QUE PUDIESE INVALIDAR EL PRESENTE CONTRATO.
            </p>
        </div>

        <p style="text-align: justify; margin: 30px 0;">
            AMBAS PARTES ESTANDO CONFORMES CON EL CONTENIDO Y CLAUSULADO DEL PRESENTE CONTRATO LO FIRMAN EL DÍA
            27 DE JUNIO DEL 2025 AL MARGEN EN CADA UNA DE SUS HOJAS Y ALCANCE EN ESTA ÚLTIMA PARA TODOS LOS EFECTOS LEGALES A QUE HAYA LUGAR.
        </p>

        <p style="text-align: center; font-size: 10; margin: 30px 0;">
            <strong><em>LOS GASTOS NOTARIALES DERIVADOS DE LA ELABORACIÓN Y FIRMA DE DICHA ESCRITURA SON CUBIERTOS POR LA PARTE VENDEDORA.</em></strong>
        </p>

        <div class="signatures">
            <div>
                <div class="signature-line"></div>
                <strong>GRUPO KSAMIA INVERSIONES E INMOBILIARIA S.A.C.</strong>
            </div>
            <div>
                <div class="signature-line"></div>
                <strong>GRUPO WASSI INVERSIONES INMOBILIARIAS S.A.C.</strong>
            </div>
            <div>
                <div class="signature-line"></div>
                <strong>GRUPO TITOS INVERSIONES E INMOBILIARIA S.A.C.</strong>
            </div>
            <div>
                <div class="signature-line"></div>
                <strong><span class="highlight">JOSEFH JORDY QUISPE MORALES<br>DNI: 70768088</span></strong>
            </div>
        </div>
    </div>
</body>
</html>