@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Product')

@section('content')
<div class="row mb-6 gy-6">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Edit Product</h5>
                <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">Back to Products</a>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger mb-6">{{ session('error') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger mb-6">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formAuthentication" method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row mb-6">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <label class="form-label" for="name">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" placeholder="Product Name" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <label class="form-label" for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $product->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $product->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="sku">SKU</label>
                            <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku', $product->sku) }}" placeholder="SKU" />
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <label class="form-label" for="price">Price</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" placeholder="Price" />
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="discount_price">Discount Price (Optional)</label>
                            <input type="number" step="0.01" name="discount_price" id="discount_price" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price', $product->discount_price) }}" placeholder="Discount Price" />
                            @error('discount_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <label class="form-label" for="quantity">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $product->quantity) }}" placeholder="Quantity" />
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <label class="form-label" for="size">Size</label>
                            <input type="text" name="size" id="size" class="form-control @error('size') is-invalid @enderror" value="{{ old('size', $product->size) }}" placeholder="Size" />
                            @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="color">Color (Optional)</label>
                            <input type="text" name="color" id="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color', $product->color) }}" placeholder="Color" />
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <label class="form-label" for="barcode">Barcode (Optional)</label>
                            <input type="text" name="barcode" id="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode', $product->barcode) }}" placeholder="Barcode" />
                            @error('barcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <label class="form-label" for="weight">Weight (Optional)</label>
                            <input type="text" name="weight" id="weight" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight', $product->weight) }}" placeholder="Weight" />
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="material">Material (Optional)</label>
                            <input type="text" name="material" id="material" class="form-control @error('material') is-invalid @enderror" value="{{ old('material', $product->material) }}" placeholder="Material" />
                            @error('material')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <label class="form-label" for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->subCategory->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="sub_category_id">Sub Category</label>
                            <select name="sub_category_id" id="sub_category_id" class="form-select @error('sub_category_id') is-invalid @enderror">
                                <option value="">Select Sub Category</option>
                                @foreach($subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}" data-category="{{ $subCategory->category_id }}" {{ old('sub_category_id', $product->sub_category_id) == $subCategory->id ? 'selected' : '' }}>{{ $subCategory->name }}</option>
                                @endforeach
                            </select>
                            @error('sub_category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <label class="form-label" for="shop_id">Shop</label>
                            <select name="shop_id" id="shop_id" class="form-select @error('shop_id') is-invalid @enderror">
                                <option value="">Select Shop</option>
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}" {{ old('shop_id', $product->shop_id) == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                                @endforeach
                            </select>
                            @error('shop_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="status">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="deactive" {{ old('status', $product->status) == 'deactive' ? 'selected' : '' }}>Deactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @include('_partials.image-upload', ['name' => 'image', 'label' => 'Product Image', 'wrapperClass' => 'mb-6', 'current' => $product->firstImagePath()])

                    <div class="mb-6">
                        <label class="form-label" for="video">Video URL (Optional)</label>
                        <input type="url" name="video" id="video" class="form-control @error('video') is-invalid @enderror" value="{{ old('video', $product->video) }}" placeholder="Video URL" />
                        @error('video')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="description">Description (Optional)</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Product Description">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('category_id').addEventListener('change', function() {
        const categoryId = this.value;
        const subCategorySelect = document.getElementById('sub_category_id');
        const allSubCategories = document.querySelectorAll('#sub_category_id option[data-category]');

        subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';

        allSubCategories.forEach(option => {
            if (!categoryId || option.getAttribute('data-category') == categoryId) {
                subCategorySelect.appendChild(option.cloneNode(true));
            }
        });
    });
</script>
@include('_partials.image-preview-script')
@endsection
