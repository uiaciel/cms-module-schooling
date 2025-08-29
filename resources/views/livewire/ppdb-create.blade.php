<div>
    <form wire:submit.prevent="createPpdb">
        <div class="mb-3">
            <label for="title" class="form-label fw-bold">Years</label>
            <input type="number" id="title" wire:model="year" class="form-control">
            @error('years')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label fw-bold">Description</label>
            <input type="text" id="title" wire:model="description" placeholder="Gelombang I" class="form-control">
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label fw-bold">Tanggal Pembukaan</label>
            <input type="date" id="title" wire:model="start_date" class="form-control">
            @error('start_date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label fw-bold">Tanggal Penutupan</label>
            <input type="date" id="title" wire:model="end_date" class="form-control">
            @error('end_date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Upload File PDF <span class="text-danger">*</span></label>
            <input type="file" class="form-control @error('brochure_pdf') is-invalid @enderror"
                wire:model="brochure_pdf" required accept="application/pdf">

            @error('brochure_pdf')
            <span class="text-danger">{{ $message }}</span>
            @enderror

            <div wire:loading wire:target="brochure_pdf" class="mt-2">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Uploading...</span>
                </div>
                <span class="ms-2">Uploading PDF...</span>
            </div>

            @if ($brochure_pdf)
            <div class="mt-2">
                <p>File PDF dipilih: **{{ $brochure_pdf->getClientOriginalName() }}**</p>
            </div>
            @endif
        </div>

        <hr />

        <div class="mb-3">
            <label for="brochure_img" class="form-label">Gambar Brosur <span class="text-danger">*</span></label>
            <input class="form-control @error('brochure_img') is-invalid @enderror" type="file" id="brochure_img"
                wire:model="brochure_img" accept="image/*" required>

            @error('brochure_img')
            <span class="text-danger">{{ $message }}</span>
            @enderror

            <div wire:loading wire:target="brochure_img" class="mt-2">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Uploading...</span>
                </div>
                <span class="ms-2">Uploading Image...</span>
            </div>

            @if ($brochure_img)
            @if (Str::startsWith($brochure_img->getMimeType(), 'image/'))
            <div class="mt-2">
                <img src="{{ $brochure_img->temporaryUrl() }}" alt="Brosur" class="img-thumbnail"
                    style="max-height: 150px;">
            </div>
            @endif
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>