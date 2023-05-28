<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <link rel="stylesheet" href="{{ url('static/css/bootstrap.min.css') }}" />
    <style>
        .title {
            text-align: center;
        }

        .bg-gray {
            background-color: #f6f6f6;
            color: #000;
        }

        .info__details {

            border-radius: 7px;

        }

        .position{
            margin: 10px auto;
            max-width: 900px;

        }
    </style>
    <title>Rapport</title>
</head>

<body>
<div class="container-fluid">
    <div style="margin-bottom:20px">
        <div class="row" style="margin-top:20px; color: #1f88ca">
            <div class="col-md-4">
                <img style="max-width: 250px; margin-bottom: 30px; height: 45px" src="{{ url('static/images/logo.png') }}" alt="logo.png">
            </div>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-md-12">
            <h2 style="background-color : #818c98; color: white; border: 1px solid #818c98; padding: 5px; margin: 20px auto; text-align: center; max-width: 900px;">BORDEREAU DE LIVRAISON</h2>
        </div>
    </div> <br> <br> <br> <br> <br>


    <div class="row" >
        <div class="col-md-12" style="width: 670px; margin: 5px auto">
            <div class="row bg-gray position flow-root">
                <div class="float-left">
                    <span class="d-block mb-1"><strong>Information Client :</strong></span>
                    <span class="d-block">Nom client: {{ $delivery->broker->name }}</span>
                    <span class="d-block">Code client: {{ $delivery->broker->code }}</span>
                    <span class="d-block">Contact client: {{ $delivery->broker->contact }}</span>
                </div>
                <div class="float-right">
                    <span class="d-block mb-1"><strong>Information livraison :</strong></span>
                    <span class="d-block">Bordereau <strong>{{ $delivery->code }}</strong></span>
                    <span class="d-block">Date émission : {{ $delivery->delivered_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

        </div>
    </div><br> <br> <br> <br> <br> <br>

    <div class="form-group row" style="margin-bottom: 30px">
        <div class="card-block table-border-style table-responsive p-2">
            <table class="table table-bordered tableaubordereau">
                <thead class="thead-light">
                <tr>
                    <th>Description</th>
                    <th>Début série</th>
                    <th>Fin série</th>
                    <th>Quantité</th>
                </tr>
                </thead>
                <tbody>
                @foreach($delivery->items as $item)
                    <tr>
                        <td><strong>{{ $delivery->attestationType->name }}</strong></td>
                        <td>{{ $item->range_start }}</td>
                        <td>{{ $item->range_end }}</td>
                        <td>{{ ($item->range_end - $item->range_start) + 1 }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan='3'> Total :</td>
                    <td>{{ $delivery->quantity }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="form-group row" style="margin-bottom:20px; max-width:900px; text-align: justify">

        <div class="col-sm-12">
            Vous voudriez bien effectuer un contrôle des documents qui vous sont adressés, c'est à dire
            vérifier que les carnets et/ou propositions sont complets, en nous retournant dès réception des
            documents
            , le double de la présente dûment signé, avec cachet de la sous agence et date.<br><br>
            <strong>NB: La livraison des attestations se fait 24h après la demande</strong>

        </div>

    </div>

    <div class="row flow-root" style="padding:20px; margin-top:20px">
        <div class="float-left">
            <label ><strong><u>POUR L'INTERMÉDIAIRE</u></strong></label>
        </div>

        <div class="float-right">
            <label><strong><u>POUR LA COMPAGNIE LE <br> GESTIONNAIRE DE STOCK</u></strong></label>
        </div>
    </div>
</div>
</body>
</html>
