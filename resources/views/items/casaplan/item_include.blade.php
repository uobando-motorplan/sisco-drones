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
    <a href="javascript:void(0);" data-toggle="modal" data-target="#itemShowModal" data-name="{{ $item->real_estate_project_id ? $item->real_estate_project->name : '' }} {{ $item->name }}" data-url="{{ route('items.gallery', $item->id) }}">
        <div class="image">
            <img src="{{ $item->imagePath().$item->image }}" class="w-100">
            <div class="price">${{ number_format($item->price) }}</div>
        </div>
    </a>
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
        <ul>
            @if ($item->feature_1)
                <li>{{ $item->feature_1 }}</li>
            @endif
            @if ($item->feature_2)
                <li>{{ $item->feature_2 }}</li>
            @endif
            @if ($item->feature_3)
                <li>{{ $item->feature_3 }}</li>
            @endif
            @if ($item->feature_4)
                <li>{{ $item->feature_4 }}</li>
            @endif
            @if ($item->feature_5)
                <li>{{ $item->feature_5 }}</li>
            @endif
            @if ($item->feature_6)
                <li>{{ $item->feature_6 }}</li>
            @endif
            @if ($item->feature_7)
                <li>{{ $item->feature_7 }}</li>
            @endif
            @if ($item->feature_8)
                <li>{{ $item->feature_8 }}</li>
            @endif
            @if ($item->feature_9)
                <li>{{ $item->feature_9 }}</li>
            @endif
        </ul>
    </div>
    @if ($item->real_estate_project_id)
        <hr>
        {{-- <div class="p-2">
            <span class="mdi mdi-city icon"></span>{{ $item->real_estate_project->location->name }}<br>
            <span class="mdi mdi-map-marker icon"></span> {{ $item->real_estate_project->address }}
        </div> --}}
        <div class="p-2">
            {{ $item->real_estate_project->address }}
        </div>
    @endif
</div>