<template>
<div class="modal fade" :id="id" tabindex="-1" :aria-labelledby="id + 'Label'" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" :id="id + 'Label'">{{ label }}</h5>
                <button ref="modalCloseBtn" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container">
                    {{ message }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button @click="$emit(id + ':success')" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            required: true
        },
        label: {
            type: String,
            default: "Warning"
        },
        message: {
            type: String,
            default: "Are you sure?"
        }
    }
}
</script>
