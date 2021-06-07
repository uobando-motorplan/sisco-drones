<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Catálogo personalizado {{ $brochure->quotation->plan->product->name }}</title>
	<style type="text/css">
		img {
			display: table;
		}
	</style>
</head>
<body>
	<table width="600" align="center" cellpadding="0" cellspacing="0" itemprop="action" itemscope="" itemtype="" bgcolor="#ffffff" style="with: 600px;">
		<tbody>
			<tr style="box-sizing: border-box; margin: 0;">
				<td class="content-block" valign="top" style="font-family: Arial,sans-serif; font-size: 16px; color: #58595B; line-height: 1.2; text-align: left; vertical-align: top; box-sizing: border-box; margin: 0; padding: 0; height: 508px;">
					<img src="{{ asset('assets/images/brochure/email/cabecera-'.strtolower($brochure->quotation->plan->product->name).'.jpg') }}" width="600" height="508" style="width: 600px; height: 508px;">
				</td>
			</tr>
			<tr style="box-sizing: border-box; margin: 0;">
				<td class="content-block" valign="top" style="font-family: Arial,sans-serif; font-size: 16px; color: #58595B; line-height: 1.2; text-align: center; vertical-align: top; box-sizing: border-box; margin: 0; padding: 40px 40px 0 40px;">
					Hola <b>{{ $brochure->quotation->customer->names }}</b>,
				</td>
			</tr>
			<tr style="box-sizing: border-box; margin: 0;">
				<td class="content-block" valign="top" style="font-family: Arial,sans-serif; font-size: 16px; color: #58595B; line-height: 1.2; text-align: center; vertical-align: top; box-sizing: border-box; margin: 0; padding: 15px 80px 0 80px;">
					hemos completado la creación de tu <b>catálogo personalizado {{ $brochure->quotation->plan->product->name }}</b>. Esperamos ayudater a alcanzar esta meta importante.
				</td>
			</tr>
			<tr style="box-sizing: border-box; margin: 0;">
				<td class="content-block" valign="top" style="font-family: Arial,sans-serif; font-size: 16px; color: #58595B; line-height: 1.2; text-align: left; vertical-align: top; box-sizing: border-box; margin: 0; padding-top: 40px; height: 108px;">
					<a href="{{ route('brochures.show', $brochure->slug) }}" style="text-decoration: none;"><img src="{{ asset('assets/images/brochure/email/boton.jpg') }}" width="600" height="108" style="width: 600px; height: 108px;"></a>
				</td>
			</tr>
			<tr style="box-sizing: border-box; margin: 0;">
				<td class="content-block" valign="top" style="font-family: Arial,sans-serif; font-size: 16px; color: #58595B; line-height: 1.2; text-align: left; vertical-align: top; box-sizing: border-box; margin: 0; padding: 0; height: 156px;">
					<img src="{{ asset('assets/images/brochure/email/pie.jpg') }}" width="600" height="156" style="width: 600px; height: 156px;">
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>