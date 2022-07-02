@php
    $priceDiff = $entry->price - $entry->boughtPrice;
@endphp

<span>@moneyConvert($priceDiff) zł</span>
