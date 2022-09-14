<html lang="en">
<head>

<!-- Font Awesome -->
<link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    rel="stylesheet"
/>
<!-- Google Fonts -->
<link
    href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
    rel="stylesheet"
/>
<!-- MDB -->
<link
    href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/5.0.0/mdb.min.css"
    rel="stylesheet"
/>
    <!-- MDB -->
    <script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/5.0.0/mdb.min.js"
    ></script>
    <title>Lego Sets @brodatydev</title>
</head>
<body>

<!-- Gallery -->
<div class="row">

    @foreach($legoSets as $legoSet)
        @if($loop->index % 2 === 0)
            <div class="col-lg-4 mb-4 mb-lg-0">
        @endif
            <img
                src="https://mdbcdn.b-cdn.net/img/Photos/Vertical/mountain2.webp"
                class="w-100 shadow-1-strong rounded mb-4"
                alt="Mountains in the Clouds"
            />
        @if($loop->index % 2 === 0)
            </div>
        @endif
    @endforeach
</div>
<!-- Gallery -->



</body>
