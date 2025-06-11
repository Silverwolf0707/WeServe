
<div class="mb-3 d-flex justify-content-between align-items-center">
    <div>
        <label for="ageStatsYear" class="form-label mb-0">Select Year</label>
        <select id="ageStatsYear" class="form-select form-select-sm" style="width: auto; display: inline-block;">
            @for ($y = now()->year; $y >= 2020; $y--)
                <option value="{{ $y }}">{{ $y }}</option>
            @endfor
        </select>
    </div>
    <div id="ageStatsLoading" style="display: none;">
        <div class="spinner-border spinner-border-sm text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <canvas id="ageStatsChart" height="150"></canvas>
    </div>
    <div class="col-md-4">
        <ul id="ageStats" class="list-group mb-3"></ul>
    </div>
</div>