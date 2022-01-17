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
                                <input v-model="customerEdit.first_name" id="first_name" class="form-control" placeholder="" required>
                            </div>
                            <div class="col">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input v-model="customerEdit.last_name" id="last_name" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <label for="email" class="form-label">Email</label>
                        <input v-model="customerEdit.email" id="email" class="form-control" placeholder="" required>
                        <label for="address_1" class="form-label">Address</label>
                        <input v-model="customerEdit.address_1" id="address_1" class="form-control" placeholder="" required>
                        <label for="address_2" class="form-label">Address Line 2 (Optional)</label>
                        <input v-model="customerEdit.address_2" id="address_2" class="form-control" placeholder="">
                        <div class="row">
                            <div class="col">
                                <label for="city" class="form-label">City</label>
                                <input v-model="customerEdit.city" id="city" class="form-control" placeholder="" required>
                            </div>
                            <div class="col">
                                <label for="state" class="form-label">State/Province</label>
                                <input v-model="customerEdit.state" id="state" class="form-control" placeholder="" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="zipcode" class="form-label">Zipcode/Postal Code</label>
                                <input v-model="customerEdit.zipcode" id="zipcode" class="form-control" placeholder="" required>
                            </div>
                            <div class="col">
                                <label for="country" class="form-label">Country</label>
                                <input v-model="customerEdit.country" id="country" class="form-control" placeholder="" required>
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
            id: "editModal",
            customerEdit: JSON.parse(JSON.stringify(this.customer))
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
                let newCustomer = {};
                for (const key in this.customerEdit) {
                    if (Object.hasOwnProperty.call(this.customerEdit, key)) {
                        const element = this.customerEdit[key];
                        if (element !== this.customer[key]) {
                            newCustomer[key] = element;
                        }
                    }
                }
                let success = true;
                // Don't bother the API if nothing changed
                if (Object.keys(newCustomer).length !== 0) {
                    // Fetch patch request to update customer
                    this.updateCustomer(newCustomer, this.customer.id);
                }
                // TODO: Handle failed requests
                if (success) {
                    // TODO: Close the modal
                }
            }
        },
        updateCustomer(updatedCustomer, customerId) {
            let method = "PATCH";
            let request = {
                'method': method,
                body: JSON.stringify(updatedCustomer),
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            fetch('api/customer/' + customerId, request)
                .then(res => res.json())
                .then(res => this.$emit("update:customer", res))
                .catch(error => console.log(error));
        }
    },
    watch: {
        customer: function() {
            this.customerEdit = JSON.parse(JSON.stringify(this.customer));
        }
    }
}
</script>
