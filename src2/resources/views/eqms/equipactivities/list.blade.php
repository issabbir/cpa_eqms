<div class="card">
    @if(Session::has('message'))
        <div
            class="alert {{Session::get('m-class') ? Session::get('m-class') : 'alert-danger'}} show"
            role="alert">
            {{ Session::get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="card-body">
        <section id="horizontal-vertical">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Equipment Activities List</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table id="searchResultTable" class="table table-sm mdl-data-table">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Roster Name</th>
                                            <th>Equipment No</th>
                                            <th>Equipment Name</th>
                                            <th>Operator Name</th>
                                            <th>Location</th>
                                            <th style="text-align: center">Action</th>
                                        </tr>
                                        </thead>

                                        <tbody id="resultDetailsBody">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
