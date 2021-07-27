<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contrato de comisión mercantil Ejecutivos Drones</title>
	<style type="text/css">
		* {
			font-size: 13.4px;
			line-height: 1.3;
		}
        img {
            display: table;
        }
		.table td, .table th {
			vertical-align: middle;
		}
		@media print {
			body {-webkit-print-color-adjust: exact;}
		}
		.page_break {
			page-break-before: always;
		}
	</style>
</head>
<body>
    <table width="430" align="left" cellpadding="0" cellspacing="0" itemprop="action" itemscope="" itemtype="" bgcolor="#fdfdfd" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; text-align: justify; box-sizing: border-box; background-color: #fdfdfd; padding: 30px 60px;">
        <tbody>
            <tr>
                <td style="vertical-align: top; padding-bottom: 15px;" valign="top">
                    <img src="{{ asset('assets/images/logo-cpmp.png') }}" alt=""  width="180" height="54">
                </td>
                <td style="vertical-align: top; padding-bottom: 15px;" valign="top" align="right">
                    <img src="{{ asset('assets/images/logo.png') }}" alt=""  width="141" height="54">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="vertical-align: top; padding-top: 30px; padding-bottom: 15px; text-align: center;" valign="top">
                    <h1 style="font-size: 24px; margin: 0;">CONTRATO DE COMISIÓN MERCANTIL</h1>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="vertical-align: top; padding-top: 15px;" valign="top">
                    <p><b>CLÁUSULA PRIMERA: COMPARECIENTES.-</b></p>
                    <p>Comparecen a la celebración del presente contrato:</p>
                    <p>1.1.- Por una parte, la compañía <b>CASAPLAN MOTORPLAN S.A.</b>, con domicilio en la ciudad de Guayaquil, legalmente representada por el señor <b>JULIAN MAURICIO VANEGAS NÚÑEZ</b>, en su calidad de <b>GERENTE GENERAL</b> y <b>REPRESENTANTE LEGAL</b>, parte a la cual se le podrá denominar simplemente <b>“LA CONTRATANTE”</b> o <b>“CASAPLAN MOTORPLAN”</b> ; y,</p>
                    <p>1.2.- Por otra parte, {{ auth()->user()->drone->sexo == 'M' ? 'el señor' :'la señora' }} <b>{{ Illuminate\Support\Str::upper(auth()->user()->drone->getFullName()) }}</b>, parte a la cual se le denominará <b>“EL/LA COMISIONISTA”</b>, <b>“EL REFERIDOR”</b> o <b>“EL/LA COMISIONISTA MERCANTIL”</b>, indistintamente.</p>
                    <p>Para efectos de este Contrato se le podrá denominar a <b>LA CONTRATANTE</b> y <b>EL COMISIONISTA</b>, <b>“PARTE”</b> de forma individual o <b>“PARTES”</b> de forma conjunta.</p>
                    <p><b>LAS PARTES</b> de forma libre y voluntaria convienen en celebrar el presente Contrato al tenor de los términos y condiciones estipuladas en las cláusulas en adelante detalladas, en lo posterior simplemente el “Contrato”.</p>
                    <p><b>CLÁUSULA SEGUNDA: ANTECEDENTES.-</b></p>
                    <p><b>2.1 CASAPLAN-MOTORPLAN S.A.</b>, es una empresa que se dedica a la comercialización de planes de compra programada para vehículos y vivienda con cobertura a nivel nacional. Para lo cual requiere de personal independiente, sin relación de dependencia, para que ingresará a prospectos interesados en adquirir planes de CASAPLAN MOTORPLAN y que seran atendidos posterior por “vendedor” de la empresa directamente.</p>
                    <p>Los "prospectos/clientes" son las personas que está interesado en nuestros productos y que llegan a la empresa a través de los diferentes canales. En especial por este programa de referidores.</p>
                    <p><b>2.2 EL COMISIONISTA</b> es una persona natural o juridica, que se dedica a la comercialización de servicios que tiene interés en mantener relaciones comerciales con LA CONTRATANTE.</p>
                    <p><b>2.3. EL COMISIONISTA</b>, cuenta con las herramientas necesarias e infraestructura física, recursos económicos siendo totalmente autónoma y suficiente para prestar dicho servicio, para lo cual ha sido calificado por <b>CASAPLAN-MOTORPLAN S.A.</b>, la misma que ha aceptada los términos, políticas de referidos de <b>LA CONTRATANTE</b> que se anexa como parte integral del presente contrato); además, de las cláusulas y condiciones expresados en éste contrato.</p>
                    <p><b>CLÁUSULA TERCERA: OBJETO.-</b></p>
                    <p>Con los antecedentes expuestos, <b>LAS PARTES</b> acuerdan, celebrar el presente contrato de comisión mercantil, en el cual <b>LA CONTRATANTE</b> pagará a <b>EL REFERIDOR</b>, una comisión por prospectos que hayan cerrado ventas con <b>CASAPLAN-MOTORPLAN S.A.</b>, en un tiempo determinado.</p>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="page_break"></div>
    <table width="440" align="left" cellpadding="0" cellspacing="0" itemprop="action" itemscope="" itemtype="" bgcolor="#fdfdfd" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; text-align: justify; box-sizing: border-box; background-color: #fdfdfd; padding: 30px 55px;">
        <tbody>
            <tr>
                <td style="vertical-align: top; padding-bottom: 15px;" valign="top">
                    <img src="{{ asset('assets/images/logo-cpmp.png') }}" alt=""  width="180" height="54">
                </td>
                <td style="vertical-align: top; padding-bottom: 15px;" valign="top" align="right">
                    <img src="{{ asset('assets/images/logo.png') }}" alt=""  width="141" height="54">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="vertical-align: top; padding-top: 15px;" valign="top">
                    <p>Dentro del proceso <b>EL REFERIDOR</b> ingresará prospectos interesados al sistema que hayan sido validados. Estos prospectos serán contactados en segunda instancia por un vendedor de CASAPLAN-MOTORPLAN S.A., quien hará la explicación completa del producto.</p>
                    <p>Es una venta cerrada para el “referidor” una vez que la misma se encuentra facturada en el sistema. Recordando que el “referidor” no hace la ejecución de ventas como tal con el cliente. El seguimiento y colocación de estatus de la venta la hace el vendedor de Casaplan Motorplan en el sistema.</p>
                    <p>Para que aplique a un plan de compensación, el prospecto asignado deberá ser cerrado en máximo dos meses luego de su ingreso a SISCO. Luego de los dos meses de haber sido creado y no finalizar la venta en ese periodo, este ya no se le contabilizará al “referidor” para su comisión.</p>
                    <p>El cierre de la venta y la documentación la realizará el vendedor de Casaplan Motorplan. El avance de la negociación podrá ser revisada por el “referidor” a través de la plataforma. EL REFERIDOR, dependiendo de cuantos prospectos lleguen a ser clientes de Casaplan Motorplan, tendrá un rango. Identificándose 3 rangos: Oro, Plata y Bronce.</p>
                    <p>Todos inician como Bronce. Para pasar a un segundo nivel deberá haber completado 10 ventas de referidos. Así mismo para seguir pasando de niveles deberá completar 10 ventas. Estas 10 ventas deberán ser hechas en un periodo de 3 meses. Para mantenerse en ese rango, el “referidor” deberá tener al menos 5 “prospectos/referidos” que completen su admisión al plan y su ingreso sea facturado en ese mismo periodo. En caso de tener menos ventas, bajará automáticamente de nivel.</p>
                    <p>Se establecerá la comisión acorde al rango y a la “Tabla de Comisiones”, que se adjunta al presente contrato. Dicha tabla de comisiones, podra variar, cada 30 días.</p>
                    <p><b>CLÁUSULA CUARTA: DURACIÓN.-</b></p>
                    <p>El contrato tendrá una duración de UN AÑO a partir de su aceptación dentro del plan de referidos y la suscripción del presente contrato. Debiendo mantenerse activo de acuerdo a las politicas de referidos, en caso de que no cumpla con los requisitos de mantenerse activo en 3 meses, se inactivará y por ende se terminará anticipadamente el presente contrato. Para renovar el presente documento se necesitará el acuerdo por escrito de las partes con 30 días de anticipación.</p>
                    <p><b>CLÁUSULA QUINTA: HONORARIOS Y FORMA DE PAGO.-</b></p>
                    <p><b>LA CONTRATANTE</b> pagará a <b>LA COMISIONISTA</b>, previa presentación de la respectiva factura, por concepto de Honorarios originados en la prestación de servicios descritos en la cláusula Tercera de este instrumento, un valor de acuerdo a las tablas de comisiones, por concepto del Plan de Compensación sobre los prospectos que lleguen a ser ventas de Casaplan Motorplan, de acuerdo a las politicas de referidos, y, que dichas ventas hayan sido direccionadas u originadas por parte de LA COMISIONISTA.</p>
                    <p><b>LA COMISIONISTA</b>, para recibir sus honorarios, en base al Plan de Compensación, debe mantener el RUC actualizado. Debe ser un comisionista activo, es decir debe tener actividad en el sistema(ingreso de prospectos) en los últimos 3 meses. Las comisiones se pagarán de acuerdo con el Plan de Compensación, (versión actualizada en el portal) siempre y cuando cumpla con los términos del Acuerdo, y emita la factura correspondiente. </p>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="page_break"></div>
    <table width="440" align="left" cellpadding="0" cellspacing="0" itemprop="action" itemscope="" itemtype="" bgcolor="#fdfdfd" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; text-align: justify; box-sizing: border-box; background-color: #fdfdfd; padding: 30px 55px;">
        <tbody>
            <tr>
                <td style="vertical-align: top; padding-bottom: 15px;" valign="top">
                    <img src="{{ asset('assets/images/logo-cpmp.png') }}" alt=""  width="180" height="54">
                </td>
                <td style="vertical-align: top; padding-bottom: 15px;" valign="top" align="right">
                    <img src="{{ asset('assets/images/logo.png') }}" alt=""  width="141" height="54">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="vertical-align: top; padding-top: 15px;" valign="top">
                    <p><b>CASAPLAN-MOTORPLAN S.A.</b>, pagará las comisiones presentadas aproximadamente 10 días hábiles luego de haber presentado la factura de “Comisión por Ventas”. El “referidor” deberá enviar una factura para poder recibir el pago de la comisión a la cual se le aplicarán las retenciones de ley, la factura debe ser emitida por el titular  de la cuenta de Casaplan Motorplan.</p>
                    <p>El pago de las comisiones podrán ser solicitadas una vez que termine el cierre del mes de comercial para Casaplan Motorplan en que la venta fuese ingresada. Como ejemplo, las ventas del mes de Enero podrán ser solicitadas desde el mes de Febrero. Las comisiones se pagarán directamente a la cuenta registrada por el referidor o por pago a terceros en una agencia bancaria.</p>
                    <p><b>CLÁUSULA SEXTA: CONFIDENCIALIDAD.-</b></p>
                    <p><b>{{ Illuminate\Support\Str::upper(auth()->user()->drone->getFullName()) }}</b>, con cédula de identidad número <b>{{ auth()->user()->drone->identification_number }}</b> declara en este mismo acto la confidencialidad de toda información provista por CASAPLAN-MOTORPLAN S.A., o por cualquiera de sus empleados o personas naturales relacionadas personal o laboralmente, que no sea de conocimiento público, y que sea designada como confidencial, o aquella información que se reciba y que conozca <b>{{ Illuminate\Support\Str::upper(auth()->user()->drone->getFullName()) }}</b> que debe ser tratada como confidencial. Específicamente y sin limitación alguna, es toda información que sea de carácter privada relacionada con información interna de la empresa; organigrama de la empresa; y conflictos internos o situaciones internas de la empresa, y cualquier otra información que CASAPLAN-MOTORPLAN S.A., haya desarrollado, adquirido o enviado a su persona.</p>
                    <p>EL COMISIONISTA declara que no podrá mostrar los materiales de promoción, ayuda de venta, productos o servicios de CASAPLAN MOTORPLAN que no haya sido puesto en el portal de drones. Por ejemplo no podrá presentar material promocional de CASAPLAN MOTORPLAN y el material promocional de otras compañías en el mismo sitio de internet, blog, tweet, puesto, texto, folleto u otro material de mercado impreso, señalización o comunicación electrónica o de otro tipo.</p>
                    <p>EL COMISIONISTA declara que no puede usar, registrar o poseer cualquier nombre de página de internet, que incluya cualquiera de los nombres comerciales de CASAPLAN MOTORPLAN, marcas registrar, nombres de servicios, marcas de servicios, nombre de productos, nombres de empresas o cualquier derivado de la misma.</p>
                    <p>La información proporcionada por cada uno de las partes durante la ejecución de la relación contractual, entre {{ Illuminate\Support\Str::upper(auth()->user()->drone->getFullName()) }} y EL CONTRATANTE, debe considerarse como confidencial e información no divulgada, para todos los efectos legales. En consecuencia, CASAPLAN-MOTORPLAN no podrá utilizar dicha información para ningún propósito que no sea para el cual fue adquirido a excepción de lo que sea expresamente indicado. No podrá divulgar dicha información a terceras personas, en ningún caso. La obligación de guardar confidencialidad permanecerá vigente aun luego de terminada la relación profesional entre las partes por cualquier causa que ello ocurra, y por tiempo indefinido. El incumplimiento de esta cláusula será considerada como divulgación no autorizada de secreto o información no divulgada, tanto para efectos civiles, administrativos o penales.</p>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="page_break"></div>
    <table width="440" align="left" cellpadding="0" cellspacing="0" itemprop="action" itemscope="" itemtype="" bgcolor="#fdfdfd" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; text-align: justify; box-sizing: border-box; background-color: #fdfdfd; padding: 30px 55px;">
        <tbody>
            <tr>
                <td style="vertical-align: top; padding-bottom: 15px;" valign="top">
                    <img src="{{ asset('assets/images/logo-cpmp.png') }}" alt=""  width="180" height="54">
                </td>
                <td style="vertical-align: top; padding-bottom: 15px;" valign="top" align="right">
                    <img src="{{ asset('assets/images/logo.png') }}" alt=""  width="141" height="54">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="vertical-align: top; padding-top: 15px;" valign="top">
                    <p>EL COMISIONISTA reconoce que en el caso que la revelación o uso no autorizados del secreto por su parte, CASAPLAN-MOTORPLAN tendrá derecho de reclamar daños y perjuicios, de acuerdo a las leyes aplicables y demás acciones civiles, administrativas y penales que en derecho le asistan. Cualquier violación a esta cláusula, será causal de terminación del presente contrato, sin perjuicios de que la contratante ejerza las acciones legales pertinentes.</p>
                    <p><b>CLÁUSULA SÉPTIMA: ÉTICA.-</b></p>
                    <p>Durante el tiempo que se encuentra como COMISIONISTA y después de haber sido aprobado su solicitud, y hasta que se encuentra activo como COMISIONISTA, no deberá mentir a nuestros prospectos o indicar características diferentes de nuestros Productos.</p>
                    <p>El comisionista si verifica que un cliente ya está registrado y siendo atendido por otro comisionista, no deberá seguir contactando a la persona ya que la responsabilidad de atención es del comisionista ya asignado.</p>
                    <p>Además está obligado a cumplir con el siguiente Código de Ética. Violaciones al Código de Ética pueden resultar en acciones disciplinarias como la desactivación del programa “drones”.</p>
                    <p><b>CÓDIGO DE ETICA</b></p>
                    <ul>
                        <li>Usted seguirá los más altos estándares de honestidad, profesionalismo e integridad en el desarrollo y operación de sus referidos de ventas.</li>
                        <li>Usted presentará información validada y se asegurará de ingresar información veraz de cada “prospecto/cliente”.</li>
                        <li>Usted no podrá hacer comentarios negativos o despectivos sobre CASAPLAN MOTORPLAN, cualquier competidor de CASAPLAN MOTORPLAN, sus empleados y productos.</li>
                        <li>Usted respetará la privacidad de los prospectos y empleados de CASAPLAN MOTORPLAN.</li>
                    </ul>
                    <p><b>CLÁUSULA OCTAVA: AUTORIZACIÓN PARA TOMAR Y UTILIZAR FOTO O VÍDEO.-</b></p>
                    <p>Con el fin de brindar apoyo al cierre de la venta del prospecto, usted autoriza a CASAPLAN MOTORPLAN S.A., revelar información personal y/o confidencial que usted ha proporcionado a CASAPLAN MOTORPLAN en relación con el prospecto o que ha desarrollado como resultado de sus actividades como comisionista.</p>
                    <p><b>CLÁUSULA NOVENA: ARBITRAJE.-</b></p>
                    <p><b>LAS PARTES</b> expresamente renuncian a la jurisdicción ordinaria y acuerdan someter toda controversia que se origine o tenga relación con la celebración, ejecución administración, interpretación terminación renovación, cancelación o cualquier otro aspecto de este contrato, ante un tribunal arbitral, conforme a la Ley de la materia. El arbitraje será en derecho, tendrá como sede la ciudad de Guayaquil y será administrativo por el Centro de Conciliación y Arbitraje de la Cámara de Comercio de Guayaquil, conforme al reglamento correspondiente. El laudo que expida el tribunal será definitivo, inapelable y de cumplimiento obligatorio por las partes. La ley que define el derecho, la forma, la competencia y el procedimiento, así como la interpretación de esta cláusula arbitral, será la ecuatoriana.</p>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="page_break"></div>
    <table width="530" align="left" cellpadding="0" cellspacing="0" itemprop="action" itemscope="" itemtype="" bgcolor="#fdfdfd" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; text-align: justify; box-sizing: border-box; background-color: #fdfdfd; padding: 30px 55px;">
        <tbody>
            <tr>
                <td style="vertical-align: top; padding-bottom: 15px;" valign="top">
                    <img src="{{ asset('assets/images/logo-cpmp.png') }}" alt=""  width="180" height="54">
                </td>
                <td style="vertical-align: top; padding-bottom: 15px;" valign="top" align="right">
                    <img src="{{ asset('assets/images/logo.png') }}" alt=""  width="141" height="54">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="vertical-align: top; padding-top: 15px;" valign="top">
                    <p><b>CLÁUSULA DÉCIMA: RELACIONES CONTRACTUALES.-</b></p>
                    <p>Se deja expresa constancia que las relaciones contractuales entre las partes, son de carácter civil y no existe relación de dependencia ni subordinación laboral. En consecuencia <b>LA CONTRATANTE</b> no estará obligada al pago de ninguna prestación al <b>COMISIONISTA</b> sea de carácter principal o adicional, compensación o indemnización prevista en las leyes laborales para los empleados, ni tampoco el pago de ninguna prestación o beneficio social.</p>
                    <p><b>CLÁUSULA DÉCIMA PRIMERO: TERMINACIÓN ANTICIPADA.-</b></p>
                    <p>Las partes podrán terminar el presente contrato en cualquier momento mediante notificación por escrito.</p>
                    <p>Y en prueba de conformidad con todo lo expuesto, firman el presente contrato por duplicado en la ciudad de Guayaquil, a los {{ Jenssegers\Date\Date::now()->format('d') }} días de {{ Jenssegers\Date\Date::now()->format('F') }} del {{ Jenssegers\Date\Date::now()->format('Y') }}.</p>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-top: 80px; padding-bottom: 15px; text-align: center;" valign="top" width="50%">
                    <b>p. CASAPLAN-MOTORPLAN S.A.</b><br>
                    <b>LA CONTRATANTE</b>
                </td>
                <td style="vertical-align: top; padding-top: 80px; padding-bottom: 15px; text-align: center;" valign="top" width="50%">
                    <b>{{ Illuminate\Support\Str::upper(auth()->user()->drone->getFullName()) }}</b><br>
                    <b>EL COMISIONISTA</b>
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top; padding-top: 80px; padding-bottom: 15px; text-align: center;" valign="top" width="50%">
                    <b>JULIAN MAURICIO VANEGAS NÚÑEZ</b><br>
                    <b>GEERENTE GENERAL</b>
                </td>
                <td style="vertical-align: top; padding-bottom: 15px; text-align: center;" valign="top" width="50%"></td>
            </tr>
        </tbody>
    </table>
</body>
</html>