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
</style>
<body>
<div>
    <h2>{{ env('APP_NAME') }}</h2>
    <h3>Presupuesto</h3>
    <p>Enviado desde la web </p>
    <br>
    <h3>Datos</h3>
    <ul>
        @if($data['nombre'])<li><strong>Nombre:</strong>{{ @$data['nombre'] }}</li>@endif
        @if($data['empresa'])<li><strong>Empresa:</strong>{{ @$data['empresa'] }}</li>@endif
        <li><strong>Email:</strong> {{ @$data['email'] }}</li>
        @if($data['telefono'])<li><strong>Teléfono:</strong>{{ @$data['telefono'] }}</li>@endif
        @if($data['revestimiento'])<li><strong>Tipo de Revestimiento:</strong>{{ @$data['revestimiento'] }}</li>@endif
        @if($data['variante'] == '1')
        <li><strong>Radio:</strong>{{ @$data['radio'] }}</li>
        @else
        <li><strong>Altura Min:</strong>{{ @$data['altura_min'] }}</li>
        <li><strong>Altura Max:</strong>{{ @$data['altura_max'] }}</li>
        <li><strong>Ancho:</strong>{{ @$data['ancho'] }}</li>
        <li><strong>Largo:</strong>{{ @$data['largo'] }}</li>

        @endif
        <br>
        <br>
        <h4>Mensaje :</h4>
        <p>
            {{ @$data['mensaje'] }}
        </p>
    </ul>
</div>
</body>
</html>
