<template>
    <form @submit.prevent="onSubmit">
    <div class="modal fade" :id="id" tabindex="-1" :aria-labelledby="id + 'Label'" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="id + 'Label'">Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <label for="first_name" class="form-label">First Name</label>
                                <input v-model="customer.first_name" id="first_name" class="form-control" placeholder="" required>
                            </div>
                            <div class="col">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input v-model="customer.last_name" id="last_name" class="form-control" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    </form>
</template>

<script>
export default {
    data() {
        return {
            id: "editModal"
        }
    },
    props: {
        customer: Object
    },
    methods: {
        onSubmit() {
            let valid = true;
            // TODO: Validate input
            if (valid) {
                // TODO: Check if customer info has been changed
                //      - Don't bother the API if nothing changed
                // TODO: Fetch put request to update customer
                // TODO: Fetch post request to create customer
            }
        }
    }
}
</script>
