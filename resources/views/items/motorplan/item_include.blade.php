<div class="inner-box">
    @if ($item->recommended)
        <div class="product-ribbon badge badge-success">Recomendado</div>
    @endif
    @if ($like)
        <div class="product-like{{ in_array($item->id, session()->get('items'))  ? ' active' : '' }}" data-toggle="tooltip" data-placement="top" title="{{ $remove ? ' Quitar' : 'Agregar/Quitar' }}" data-id="{{ $item->id }}" data-add-url="{{ route('items.add') }}" data-remove-url="{{ route('items.remove') }}">
            <a href="{{ $remove ? route('items.remove', "id=$item->id&back=1") : 'javascript:void(0);'}} ">
                <i class="mdi mdi-heart"></i>
            </a>
        </div>
    @endif
    <a href="javascript:void(0);" data-toggle="modal" data-target="#itemShowModal" data-name="{{ $item->preference->name }} {{ $item->name }}" data-url="{{ route('items.gallery', $item->id) }}">
        <div class="image">
            <img src="{{ $item->imagePath().$item->image }}" class="w-100">
            <div class="price">${{ number_format($item->price) }}</div>
        </div>
    </a>
    <div class="brand d-flex justify-content-start h-100">
        <img src="{{ $item->preference->imagePath().$item->preference->image }}" class="brand-logo my-auto">
        <h3 class="my-auto">{{ $item->preference->name }} <span class="text-primary">{{ $item->name }}</span></h3>
    </div>
    <div class="lower-box">
        <ul class="details text-truncate">
            <li><span class="mdi mdi-calendar-clock icon"></span>{{ $item->main_feature_1 }}</li>
            <li><span class="mdi mdi-gas-station icon"></span>{{ $item->main_feature_2 }}</li>
            <li><span class="mdi mdi-engine icon"></span>{{ $item->main_feature_3 }}</li>
        </ul>
    </div>
    <div class="features">
        @if ($item->feature_1)
            <span>&#8226</span> {{ $item->feature_1 }} 
        @endif
        @if ($item->feature_2)
            <span>&#8226</span> {{ $item->feature_2 }} 
        @endif
        @if ($item->feature_3)
            <span>&#8226</span> {{ $item->feature_3 }} 
        @endif
        @if ($item->feature_4)
            <span>&#8226</span> {{ $item->feature_4 }} 
        @endif
        @if ($item->feature_5)
            <span>&#8226</span> {{ $item->feature_5 }} 
        @endif
        @if ($item->feature_6)
            <span>&#8226</span> {{ $item->feature_6 }} 
        @endif
        @if ($item->feature_7)
            <span>&#8226</span> {{ $item->feature_7 }} 
        @endif
        @if ($item->feature_8)
            <span>&#8226</span> {{ $item->feature_8 }} 
        @endif
        @if ($item->feature_9)
            <span>&#8226</span> {{ $item->feature_9 }} 
        @endif
    </div>
</div>