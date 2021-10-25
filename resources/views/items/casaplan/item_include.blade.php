<div class="inner-box">
    @if ($item->recommended)
        <div class="product-ribbon badge badge-success">Recomendado</div>
    @endif
    @if ($like)            
        <div class="product-like{{ in_array($item->id, session()->get('items'))  ? ' active' : '' }}" data-toggle="tooltip" data-placement="top" title="{{ $remove ? ' Quitar' : 'Agregar/Quitar' }}"" data-id="{{ $item->id }}" data-add-url="{{ route('items.add') }}" data-remove-url="{{ route('items.remove') }}">
            <a href="{{ $remove ? route('items.remove', "id=$item->id&back=1") : 'javascript:void(0);'}}">
                <i class="mdi mdi-heart"></i>
            </a>
        </div>
    @endif
    @if ($item->url)
        <div class="product-tour" data-toggle="tooltip" data-placement="top" title="Tour virtual">
            <a data-fancybox="single-{{ $item->id }}" data-src="{{ $item->url }}" data-type="iframe" href="{{ $item->url }}" id="iframecontent-{{ $item->id }}">
                <i class="mdi mdi-binoculars"></i>
            </a>
        </div>
    @endif
    <div class="image">
        <a href="javascript:void(0);" data-fancybox="gallery-{{ $item->id }}" data-src="{{ $item->imagePath().$item->image }}" data-caption="<b> {{ mb_convert_case(($item->real_estate_project_id ? $item->real_estate_project->name : ''), MB_CASE_UPPER, 'UTF-8') }} {{ mb_convert_case($item->name, MB_CASE_UPPER, "UTF-8") }}</b><br>{{ ($item->real_estate_project_id ? $item->real_estate_project->address : '') }}">
            <img src="{{ $item->imagePath().$item->image }}" class="w-100">
        </a>
        @foreach ($item->photos as $photo)
            <a data-fancybox="gallery-{{ $item->id }}" data-src="{{ $photo->imagePath().$photo->name }}" data-caption="<b> {{ mb_convert_case(($item->real_estate_project_id ? $item->real_estate_project->name : ''), MB_CASE_UPPER, 'UTF-8') }} {{ mb_convert_case($item->name, MB_CASE_UPPER, "UTF-8") }}</b><br>{{ ($item->real_estate_project_id ? $item->real_estate_project->address : '') }}"></a>
        @endforeach
        <div class="price">${{ number_format($item->price) }}</div>
    </div>
    <div class="brand d-flex justify-content-start h-100">
        @if ($item->real_estate_project_id)
            <img src="{{ $item->real_estate_project->imagePath().$item->real_estate_project->image }}" class="brand-logo my-auto">
        @endif
        <h3 class="my-auto">{{ $item->real_estate_project_id ? $item->real_estate_project->name : '' }} <span class="text-primary">{{ $item->name }}</span></h3>
    </div>
    @if ($combined_plans)
        <div class="combined-plans">
            En planes de ${{ number_format($combined_plans['plan_1']['amount']) }} + ${{ number_format($combined_plans['plan_2']['amount']) }} 
            con cuotas fijas de ${{ number_format($combined_plans['plan_1']['monthly_payment']) }} + ${{ number_format($combined_plans['plan_2']['monthly_payment']) }}
        </div>
    @endif
    <div class="lower-box">
        <ul class="details text-truncate">
            @if ($item->real_estate_project_id)
                <li><span class="mdi mdi-city icon"></span>{{ $item->real_estate_project->location->name }}</li>
            @endif
            <li><span class="mdi mdi-arrow-expand icon"></span>{{ $item->main_feature_1 }}</li>
            <li><span class="mdi mdi-bed icon"></span>{{ $item->main_feature_2 }}</li>
            <li><span class="mdi mdi-shower icon"></span>{{ $item->main_feature_3 }}</li>
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
    @if ($item->real_estate_project_id)
        <div class="px-2 pb-2">
            {{ $item->real_estate_project->address }}
        </div>
    @endif
</div>