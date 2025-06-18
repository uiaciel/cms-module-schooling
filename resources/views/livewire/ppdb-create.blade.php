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

        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
