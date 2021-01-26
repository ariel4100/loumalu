<!DOCTYPE html>

<html>
<style>
    body {
        font-family: Helvetica, sans-serif;
        color:#494949;
    }

    ul {
        list-style: none;
    }

    div{
        background-color: #F8F8F8;
        width: 85%;
        border-radius:20px;
        padding: 1rem 2rem;
    }

    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>
<body>
<div>
    <h2>{{ env('APP_NAME') }}</h2>
    <h3>Reclamos</h3>
    <p>Enviado desde la web </p>
    <br>
    <h3>Datos</h3>
    <ul>
        @if($data['nombre'])<li><strong>Nombre de Cliente:</strong>{{ @$data['nombre'] }}</li>@endif
        @if($data['nombre'])<li><strong>Usuario de Cliente:</strong>{{ @$data['nombre'] }}</li>@endif
        @if($data['email'])<li><strong>Email de Cliente:</strong>{{ @$data['email'] }}</li>@endif

        @if($data['factura'])<li><strong>Factura:</strong>{{ @$data['factura'] }}</li>@endif
        @if($data['transporte'])<li><strong>Transporte:</strong>{{ @$data['transporte'] }}</li>@endif
        @if($data['guia'])<li><strong>Guia:</strong>{{ @$data['guia'] }}</li>@endif
        @if($data['factura'])<li><strong>Factura:</strong>{{ @$data['factura'] }}</li>@endif
        @if($data['fecha'])<li><strong>Fecha:</strong>{{ @$data['fecha'] }}</li>@endif
        @if($data['autorizo'])<li><strong>Autorizo:</strong>{{ @$data['autorizo'] }}</li>@endif
        @if($data['controlo'])<li><strong>Controlo:</strong>{{ @$data['controlo'] }}</li>@endif

        <br>
        <br>
            @if(count($items) > 0)
            <h4>Devolución :</h4>
                <table>
                    <tr>
                        <th>Codigo</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                    </tr>
                    @foreach($items as $value)
                        <tr>
                            <td>{{ @$value['codigo'] }}</td>
                            <td>{{ @$value['descripcion'] }}</td>
                            <td>{{ @$value['cantidad'] }}</td>
                        </tr>
                    @endforeach

                </table>


            @endif
        <br>
        <br>
        <h4>Motivo :</h4>
        <p>
            {{ @$data['motivo'] }}
        </p>
    </ul>
</div>
</body>
</html>
