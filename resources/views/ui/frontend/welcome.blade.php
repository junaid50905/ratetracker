<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('ui/frontend') }}/assets/css/style.css">

    <style>
        .table-responsive .table {
            font-size: calc(20px + 1.5vw);
            background-color: transparent;
        }

        .table th,
        .table td {
            background-color: transparent !important;
            color: white;
        }

        .table-bordered th,
        .table-bordered td {
            border-color: white;
        }

        div#carouselExampleInterval {
            height: 100vh;
            margin: -16px;
        }

        .bg-primary {
            --bs-bg-opacity: 1;
            background-color: rgb(4 54 127) !important;
            height: 100vh;
        }
    </style>
    <title>Slides show</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="p-3 m-0 border-0 bd-example m-0 border-0">

    <!-- Example Code -->
    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item bg-primary active" data-bs-interval="{{ $exchange_rate_duration }}">
                <h1 class="text-danger text-center"
                    style="margin-top: 15vh; font-size: calc(20px + 3vw);; margin-bottom: 5vh;">Cash Foreign Currency
                </h1>
                <div class="table-responsive" style="margin: 0 10vw;">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Currency</th>
                                <th>Code</th>
                                <th>Buying (BDT)</th>
                                <th>Selling (BDT)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($exchange_rates as $exchange_rate)
                                @php
                                    $currency = DB::table('currencies')->find($exchange_rate->currency_id);
                                @endphp
                                <tr>
                                    <td>{{ $currency->name }} ({{ $currency->symbol }})</td>
                                    <td>{{ $currency->code }}</td>
                                    <td>{{ $exchange_rate->buying_rate }}</td>
                                    <td>{{ $exchange_rate->selling_rate }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @foreach ($images as $image)
                <div class="carousel-item" data-bs-interval="{{ $image->duration }}">
                    <img src="{{ asset('ui/uploads/image') }}/{{ $image->image }}" class="d-block w-100">
                </div>
            @endforeach

            @foreach ($videos as $video)
                <div class="carousel-item" data-bs-interval="{{ $video->duration }}">
                    <video class="w-100 h-100 video-player" autoplay muted playsinline>
                        <source src="{{ asset('ui/uploads/video') }}/{{ $video->video }}" type="video/mp4">
                    </video>
                </div>
            @endforeach
        </div>
        {{-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button> --}}
    </div>

    <!-- JavaScript to reset video to the start when it becomes active -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var carousel = document.getElementById('carouselExampleInterval');
            carousel.addEventListener('slid.bs.carousel', function(event) {
                var activeItem = event.relatedTarget; // Get the new active carousel item
                var video = activeItem.querySelector('video');

                if (video) {
                    // Pause and reset the video, then play it from the start
                    video.pause();
                    video.currentTime = 0;
                    video.play();
                }
            });
        });
    </script>

</body>

</html>
