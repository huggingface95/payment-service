<?php

namespace App\DTO\Requisite;

class RequisiteSendEmailDTO
{
    public string $content;

    public static function transform(array $args): self
    {
        $dto = new self();

        $storagePath = storage_path('pdf');
		$imgLogoBase64 = base64_encode(file_get_contents($args['logo_path']));
        $cssStyles = file_get_contents($storagePath . '/css/main.css');

        $dto->content = '<!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="utf-8">

            <title></title>
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
            <style>
			@font-face {
				font-family: "roboto";
				font-weight: 500;
				font-style: normal;
				src: url(' . storage_path('fonts/roboto-medium.ttf') . ') format("truetype");
			}
			@font-face {
				font-family: "roboto";
				font-weight: 400;
				font-style: normal;
				src: url(' . storage_path('fonts/roboto-regular.ttf') . ') format("truetype");
			}
			' . $cssStyles . '
			</style>
        </head>

        <body>
            <header class="p100">
                <div class="container">
                    <img src="data:image/jpeg;charset=utf-8;base64,' . $imgLogoBase64 . '" alt="" style="width: 230px">
                </div>
            </header>
            <main>
                <div class="title-page">
                    <div class="container">
                        <h1>FOR ' . $args['currency'] . ' TRANSFER</h1>
                    </div>
                </div>
                <div class="p100">
                    <div class="container">
                        <table class="table">
                            <tr>
                                <th>Beneficiary</th>
                                <td>' . $args['beneficiary'] . '</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>' . $args['address'] . '</td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td>' . $args['country'] . '</td>
                            </tr>
                            <tr>
                                <th>Bank Name</th>
                                <td>' . $args['bank_name'] . '</td>
                            </tr>
                            <tr>
                                <th>IBAN/Account #</th>
                                <td>' . $args['iban'] . '</td>
                            </tr>
                            <tr>
                                <th>Swift Code</th>
                                <td>' . $args['swift_code'] . '</td>
                            </tr>
                            <tr>
                                <th>Bank Address</th>
                                <td>' . $args['bank_address'] . '</td>
                            </tr>
                            <tr>
                                <th>Bank Country</th>
                                <td>' . $args['bank_country'] . '</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </main>
        </body>

        </html>';

        return $dto;
    }
}
