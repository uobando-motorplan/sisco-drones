<!-- start row -->
<div class="row">
    <div class="col-xl-8">
        <div class="card rounded-0 buscador">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('items.search', $product->id) }}">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="card-title mb-3">¿BUSCAS UN VEHÍCULO <span class="text-primary">PARA TU REFERIDO?</span></h4>
                            <div class="row align-items-end">
                                <div class="col-md-6 mb-3">
                                    <select name="category" id="category" class="custom-select" data-validation="">
                                        <option value="">- Categoría -</option>
                                        @foreach ($categories as $id => $name)
                                            <option value="{{ $id }}"{{ $category ? ($category->id == $id ? 'selected' : '') : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <select name="brand" id="brand" class="custom-select" data-validation="">
                                        <option value="">- Marca -</option>
                                        @foreach ($preferences as $id => $name)
                                            <option value="{{ $id }}"{{ $brand ? ($brand->id == $id ? 'selected' : '') : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-8 col-lg-7" style="height: 63px">
                                    <input type="hidden" name="range" id="range" value="" class="js-range-slider" data-url="{{ route('plans.fee_range', App\Product::MOTORPLAN) }}" data-plans-url="{{ route('plans.index', App\Product::MOTORPLAN) }}">
                                    <div class="price-range"></div>
                                </div>
                                <div class="col-xl-4 col-lg-5 mt-3 mt-lg-auto">
                                    <button type="submit" class="btn btn-primary w-100 waves-effect waves-light">BUSCAR</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <input type="hidden" id="product_id" value="{{ App\Product::MOTORPLAN }}">
                <input type="hidden" id="from" value="{{ $from }}">
                <input type="hidden" id="to" value="{{ $to }}">
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="p-4 bg-gris ayuda">
            <div class="row h-100">
                <div class="col-xl-12 col-md-7 my-auto">
                    <p class="mb-3 mb-md-0">Personaliza un <strong class="text-primary">Catálogo MotorPlan</strong> para tu referido con vehículos en los que está interesado. Para agregar o quitar vehículos al brochure, haz clic sobre su respectivo botón de deseo.</p>
                </div>
                <div class="col-xl-12 col-md-5 mt-auto mb-auto mt-lg-auto mb-xl-0">
                    <a href="{{ route('brochures.create', $product->id) }}"class="btn {{ count(session()->get('items')) > 0  ? 'btn-outline-primary' : 'btn-outline-secondary disabled' }} w-100 waves-effect waves-light send-brochure">CREAR Y ENVIAR CATÁLOGO</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->
