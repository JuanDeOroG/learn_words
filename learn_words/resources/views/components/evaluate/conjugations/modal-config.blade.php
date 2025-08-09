<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="fillConfigForm" method="GET" action="{{ route('evaluate.fillConjugations') }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Configure Exercise</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    {{-- sección de conjugaciones agrupadas --}}
                    <div class="mb-4 p-3 border rounded bg-light">
                        <h6 class="mb-3">Select Conjugations to Evaluate</h6>
                        <div class="row">
                            @foreach ($conjugations as $conjugation)
                                <div class="col-6 col-md-6 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="{{ $conjugation->id }}"
                                            id="conj_{{ $conjugation->id }}" name="conjugations[]">
                                        <label class="form-check-label" for="conj_{{ $conjugation->id }}">
                                            {{ $conjugation->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- input de columnas destacado y separado --}}
                    <div class="mb-3 p-3 border rounded bg-white shadow-sm">
                        <label for="columns" class="form-label ">Número de columnas por palabra
                        </label>
                        <input type="number" class="form-control" id="columns" name="columns" min="1"
                            max="{{count($conjugations)}}" value="4">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="setDefaults()">Default</button>
                    <button type="button" class="btn btn-primary" onclick="submitFillConfig()">Evaluate</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    // set default values
    function setDefaults() {
        document.getElementById('to_fill').value = 2;
        document.getElementById('columns').value = 5;
    }
    // submit the form
    function submitFillConfig() {
        document.getElementById('fillConfigForm').submit();
    }
</script>
