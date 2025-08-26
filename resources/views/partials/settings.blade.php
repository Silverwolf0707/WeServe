@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <h1 class="mb-4">Config Settings</h1>

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Categories</h5>
                    </div>
                    <div class="list-group list-group-flush" id="configTabs" role="tablist">
                        <a class="list-group-item list-group-item-action active" id="general-tab" data-bs-toggle="list"
                            href="#general" role="tab" aria-controls="general">General</a>
                        <a class="list-group-item list-group-item-action" id="notifications-tab" data-bs-toggle="list"
                            href="#notifications" role="tab" aria-controls="notifications">Notifications</a>
                        <a class="list-group-item list-group-item-action" id="personalization-tab" data-bs-toggle="list"
                            href="#personalization" role="tab" aria-controls="personalization">Personalization</a>
                        <a class="list-group-item list-group-item-action" id="datacontrol-tab" data-bs-toggle="list"
                            href="#datacontrol" role="tab" aria-controls="datacontrol">Data Control</a>
                        <a class="list-group-item list-group-item-action" id="security-tab" data-bs-toggle="list"
                            href="#security" role="tab" aria-controls="security">Security</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content" id="configTabsContent">

                            <div class="tab-pane fade show active" id="general" role="tabpanel"
                                aria-labelledby="general-tab">
                                <h5>General Settings</h5>
                                <hr>
                                <div class="mb-3">
                                    <label class="form-label">Select Theme</label>
                                    <select class="form-select" id="themeSelector">
                                        <option value="light">Light</option>
                                        <option value="dark">Dark</option>
                                    </select>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="notifications" role="tabpanel"
                                aria-labelledby="notifications-tab">
                                <h5>Notifications</h5>
                                <hr>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" checked disabled>
                                    <label class="form-check-label">Email Notifications Enabled</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" disabled>
                                    <label class="form-check-label">SMS Notifications Disabled</label>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="personalization" role="tabpanel"
                                aria-labelledby="personalization-tab">
                                <h5>Personalization / Theme</h5>
                                <hr>
                                <div class="mb-3">
                                    <label class="form-label">Select Theme</label>
                                    <select class="form-select">
                                        <option>Light</option>
                                        <option>Dark</option>
                                    </select>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="datacontrol" role="tabpanel" aria-labelledby="datacontrol-tab">
                                <h5>Data Control</h5>
                                <hr>
                                <p class="text-warning">
                                    ⚠️ Caution: This will delete all database data bypassing soft delete.
                                </p>
                                <form method="POST" action="{{ route('admin.settings.deleteAll') }}"
                                    onsubmit="return confirm('Are you absolutely sure? This will permanently delete ALL data!');">
                                    @csrf
                                    @method('DELETE')
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                                        <label class="form-check-label" for="confirmDelete">
                                            Yes, I understand. Delete all data.
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-danger">Delete All Data</button>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                                <h5>Security</h5>
                                <hr>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" checked disabled>
                                    <label class="form-check-label">Two-Factor Authentication Enabled</label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password Expiry (days)</label>
                                    <input type="text" class="form-control" value="90" disabled>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection