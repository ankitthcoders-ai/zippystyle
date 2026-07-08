@extends('layouts/contentNavbarLayout')

@section('title', ' Vertical Layouts - Forms')

@section('content')
<!-- Basic Layout -->
<div class="row mb-6 gy-6">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Add Product</h5>
                <small class="text-body float-end">Product Details</small>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success mb-6">
                        {{ session('success') }}
                    </div>
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

                <form id="formAuthentication" method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-6">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <label class="form-label" for="name">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Product Name" />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <label class="form-label" for="gender">Gender</label>
                            <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="sku">SKU</label>
                            <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}" placeholder="SKU" />
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <label class="form-label" for="price">Price</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="Price" />
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="discount_price">Discount Price (Optional)</label>
                            <input type="number" step="0.01" name="discount_price" id="discount_price" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price') }}" placeholder="Discount Price" />
                            @error('discount_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <label class="form-label" for="quantity">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" placeholder="Quantity" />
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <label class="form-label" for="size">Size</label>
                            <input type="text" name="size" id="size" class="form-control @error('size') is-invalid @enderror" value="{{ old('size') }}" placeholder="Size" />
                            @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="color">Color (Optional)</label>
                            <input type="text" name="color" id="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color') }}" placeholder="Color" />
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-6">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <label class="form-label" for="barcode">Barcode (Optional)</label>
                            <input type="text" name="barcode" id="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode') }}" placeholder="Barcode" />
                            @error('barcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <label class="form-label" for="weight">Weight (Optional)</label>
                            <input type="text" name="weight" id="weight" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight') }}" placeholder="Weight" />
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="material">Material (Optional)</label>
                            <input type="text" name="material" id="material" class="form-control @error('material') is-invalid @enderror" value="{{ old('material') }}" placeholder="Material" />
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
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
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
                                    <option value="{{ $subCategory->id }}" data-category="{{ $subCategory->category_id }}" {{ old('sub_category_id') == $subCategory->id ? 'selected' : '' }}>{{ $subCategory->name }}</option>
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
                                    <option value="{{ $shop->id }}" {{ old('shop_id') == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                                @endforeach
                            </select>
                            @error('shop_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="status">Status</label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="deactive" {{ old('status') == 'deactive' ? 'selected' : '' }}>Deactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @include('_partials.image-upload', ['name' => 'image', 'label' => 'Product Image', 'wrapperClass' => 'mb-6'])

                    <div class="mb-6">
                        <label class="form-label" for="video">Video URL (Optional)</label>
                        <input type="url" name="video" id="video" class="form-control @error('video') is-invalid @enderror" value="{{ old('video') }}" placeholder="Video URL" />
                        @error('video')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="form-label" for="description">Description (Optional)</label>
                        <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" placeholder="Product Description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Subcategory filter
    document.getElementById('category_id').addEventListener('change', function() {
        const categoryId = this.value;
        const subCategorySelect = document.getElementById('sub_category_id');
        const allSubCategories = document.querySelectorAll('#sub_category_id option[data-category]');

        // Reset subcategory select
        subCategorySelect.innerHTML = '<option value="">Select Sub Category</option>';

        if (categoryId) {
            allSubCategories.forEach(option => {
                if (option.getAttribute('data-category') == categoryId) {
                    subCategorySelect.appendChild(option.cloneNode(true));
                }
            });
        } else {
            allSubCategories.forEach(option => {
                subCategorySelect.appendChild(option.cloneNode(true));
            });
        }
    });
</script>
@include('_partials.image-preview-script')
@endsection
