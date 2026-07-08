@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Shop')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Shop</h5>
            </div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="formAuthentication" method="POST" action="{{ route('shops.update', $shop->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="form-label" for="name">Shop Name</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $shop->name) }}" placeholder="Shop Name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $shop->email) }}" placeholder="Email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $shop->phone) }}" placeholder="Phone" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label" for="shop_number">Shop Number</label>
                            <input type="text" name="shop_number" id="shop_number" class="form-control @error('shop_number') is-invalid @enderror" value="{{ old('shop_number', $shop->shop_number) }}" placeholder="Shop Number">
                            @error('shop_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="gst_number">GST Number</label>
                            <input type="text" name="gst_number" id="gst_number" class="form-control @error('gst_number') is-invalid @enderror" value="{{ old('gst_number', $shop->gst_number) }}" placeholder="GST Number">
                            @error('gst_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="website">Website</label>
                            <input type="url" name="website" id="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $shop->website) }}" placeholder="Website">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label" for="facebook">Facebook</label>
                            <input type="url" name="facebook" id="facebook" class="form-control @error('facebook') is-invalid @enderror" value="{{ old('facebook', $shop->facebook) }}" placeholder="Facebook">
                            @error('facebook')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="twitter">Twitter</label>
                            <input type="url" name="twitter" id="twitter" class="form-control @error('twitter') is-invalid @enderror" value="{{ old('twitter', $shop->twitter) }}" placeholder="Twitter">
                            @error('twitter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="instagram">Instagram</label>
                            <input type="url" name="instagram" id="instagram" class="form-control @error('instagram') is-invalid @enderror" value="{{ old('instagram', $shop->instagram) }}" placeholder="Instagram">
                            @error('instagram')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="linkedin">LinkedIn</label>
                        <input type="url" name="linkedin" id="linkedin" class="form-control @error('linkedin') is-invalid @enderror" value="{{ old('linkedin', $shop->linkedin) }}" placeholder="LinkedIn">
                        @error('linkedin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="address">Address</label>
                        <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" placeholder="Address" required>{{ old('address', $shop->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label" for="city">City</label>
                            <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $shop->city) }}" placeholder="City" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="state">State</label>
                            <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $shop->state) }}" placeholder="State" required>
                            @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="zip">Zip</label>
                            <input type="text" name="zip" id="zip" class="form-control @error('zip') is-invalid @enderror" value="{{ old('zip', $shop->zip) }}" placeholder="Zip" required>
                            @error('zip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label" for="country">Country</label>
                            <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', $shop->country) }}" placeholder="Country" required>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    @include('_partials.image-upload', ['name' => 'image', 'label' => 'Shop Image', 'current' => $shop->image])

                    <div class="mb-4">
                        <label class="form-label" for="description">Description</label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" placeholder="Description">{{ old('description', $shop->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="status">Status</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="active" {{ old('status', $shop->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="deactive" {{ old('status', $shop->status) == 'deactive' ? 'selected' : '' }}>Deactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@include('_partials.image-preview-script')
@endsection
