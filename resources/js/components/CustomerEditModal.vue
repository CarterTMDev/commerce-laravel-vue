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
                        <label for="email" class="form-label">Email</label>
                        <input v-model="customer.email" id="email" class="form-control" placeholder="" required>
                        <label for="address_1" class="form-label">Address</label>
                        <input v-model="customer.address_1" id="address_1" class="form-control" placeholder="" required>
                        <label for="address_2" class="form-label">Address Line 2 (Optional)</label>
                        <input v-model="customer.address_2" id="address_2" class="form-control" placeholder="">
                        <div class="row">
                            <div class="col">
                                <label for="city" class="form-label">City</label>
                                <input v-model="customer.city" id="city" class="form-control" placeholder="" required>
                            </div>
                            <div class="col">
                                <label for="state" class="form-label">State/Province</label>
                                <input v-model="customer.state" id="state" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="zipcode" class="form-label">Zipcode/Postal Code</label>
                                <input v-model="customer.zipcode" id="zipcode" class="form-control" placeholder="" required>
                            </div>
                            <div class="col">
                                <label for="country" class="form-label">Country</label>
                                <input v-model="customer.country" id="country" class="form-control" placeholder="" required>
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
