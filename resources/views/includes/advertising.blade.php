@if (!isset($inPostDetail) && !isset($isCreated))
    @if ($advertising)
        @foreach ($advertising as $ad)
        <div class="card rounded-large border shadow-sm mb-3 position-relative p-3 advertising">

            <div class="d-flex">
                <div class="flex-shrink-0">
                    <img class="img-fluid rounded img-thanks-share" width="150"
                        src="{{ Helper::getFile(config('path.ads').$ad->image) }}">
                </div>
                <div class="flex-grow-1 ml-3">
                    <h5 class="mb-1">{{ $ad->title }}</h5>
                    {{ $ad->description }}
                    <small class="d-block w-100 text-muted"><i class="bi-badge-ad mr-1"></i> {{ __('general.advertising')}}</small>
                </div>
            </div>

            <a href="{{ url('click/ad', $ad->id) }}" target="_blank" class="stretched-link"></a>
        </div>

        @php $ad->impressions(); @endphp
        @endforeach

    @endif
@endif