<div>
    <form wire:submit.prevent="createGraduation">
        <div class="mb-3">
            <label for="title" class="form-label fw-bold">Years</label>
            <input type="number" id="title" wire:model="year" class="form-control">
            @error('years')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="open_date" class="form-label fw-bold">Tanggal Pembukaan</label>
            <input type="date" id="title" wire:model="open_date" class="form-control">
            @error('open_date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="close_date" class="form-label fw-bold">Tanggal Penutupan</label>
            <input type="date" id="title" wire:model="close_date" class="form-control">
            @error('close_date')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="status" class="form-label fw-bold">Status</label>
            <select class="form-select" wire:model="status" aria-label="Default select example">
                <option selected>Open this select menu</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>

            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>