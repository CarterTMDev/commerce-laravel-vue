<template>
    <form @submit.prevent="onSubmit" @modal:mode="mode = $event">
    <div class="modal fade" :id="id" tabindex="-1" :aria-labelledby="id + 'Label'" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="id + 'Label'">{{ mode === "add" ? "Add" : "Edit" }} Customer</h5>
                    <button ref="modalCloseBtn" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <p v-if="error" class="text-danger">
                            {{ errorMessage }}
                        </p>
                        <p v-if="invalid" class="text-danger">
                            {{ invalidMessage }}
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">{{ mode === "add" ? "Add customer" : "Save changes" }}</button>
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
            customerEdit: JSON.parse(JSON.stringify(this.customer)),
            mode: {
                type: String,
                default: 'add',
                validator: function(val) {
                    return ['add', 'edit'].indexOf(val) !== -1;
                }
            },
            error: false,
            errorMessage: "",
            invalid: false,
            invalidMessage: ""
        }
    },
    props: {
        customer: Object,
        id: {
            type: String,
            required: true
        }
    },
    mounted() {
        this.$parent.$on('modal:mode', this.setMode);
    },
    methods: {
        async onSubmit() {
            this.error = false;
            this.validateInput();
            if (!this.invalid) {
                // If the email isn't null, set it to all lower-case
                if (this.customerEdit['email'] !== null) {
                    this.customerEdit['email'] = this.customerEdit['email'].toString().toLowerCase();
                }
                // Process the customer data
                let success = true;
                switch (this.mode) {
                    case "edit":
                        // Store all changed customer info in newCustomer object
                        let newCustomer = {};
                        for (const key in this.customerEdit) {
                            if (Object.hasOwnProperty.call(this.customerEdit, key)) {
                                const element = this.customerEdit[key];
                                if (element !== this.customer[key]) {
                                    newCustomer[key] = element;
                                }
                            }
                        }
                        // Don't bother the API if nothing changed
                        if (Object.keys(newCustomer).length !== 0) {
                            success = await this.updateCustomer(newCustomer, this.customer.id);
                        }
                        if (success) {
                            this.closeModal();
                        }
                        break;
                    case "add":
                        success = await this.addCustomer(this.customerEdit);
                        if (success) {
                            this.closeModal();
                        }
                        break;
                }
            }
        },
        async updateCustomer(updatedCustomer, customerId) {
            let method = "PATCH";
            let request = {
                'method': method,
                body: JSON.stringify(updatedCustomer),
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            return fetch(window.location.origin + '/api/customers/' + customerId, request)
                .then(res => {
                    if (res.ok) {
                        return res.json()
                                .then(res => {
                                    this.$emit("update:customer", res);
                                    return true;
                                }, () => { return false });
                    } else {
                        return false;
                    }
                }).catch(() => { return false });
        },
        async addCustomer(newCustomer) {
            let request = {
                method: "POST",
                body: JSON.stringify(newCustomer),
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            this.errorMessage = '';
            this.error = false;
            return fetch(window.location.origin + '/api/customers', request)
                .then(res => {
                    if (res.ok) {
                        return res.json()
                                .then(res => {
                                    this.$emit("add:customer", res);
                                    return true;
                                },
                                () => {
                                    this.error = true;
                                    this.errorMessage = 'An error occurred. Please try again.';
                                    return false;
                                });
                    } else {
                        this.error = true;
                        this.errorMessage = 'An error occurred. Please try again.';
                        if (res.status == 409) {
                            this.errorMessage = 'That email is taken. Please enter a different email.';
                            return false;
                        }
                    }
                }).catch(() => {
                    this.error = true;
                    this.errorMessage = 'An error occurred. Please try again.';
                    return false;
                });
        },
        setMode(newMode) {
            this.mode = newMode;
        },
        closeModal() {
            this.$refs.modalCloseBtn.click();
        },
        validateInput() {
            this.invalid = false;
            this.invalidMessage = "";
            // strings: limit to 255
            if (this.customerEdit['first_name'].toString().length > 255
                    || this.customerEdit['last_name'].toString().length > 255
                    || this.customerEdit['email'].toString().length > 255
                    || this.customerEdit['address_1'].toString().length > 255
                    || (this.customerEdit['address_2'] !== null
                        && this.customerEdit['address_2'].toString().length > 255)
                    || this.customerEdit['city'].toString().length > 255
                    || this.customerEdit['state'].toString().length > 255
                    || this.customerEdit['zipcode'].toString().length > 255
                    || this.customerEdit['country'].toString().length > 255) {
                this.invalidMessage = "No text field should have more than 255 characters.";
                this.invalid = true;
            }
            // check email
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!this.customerEdit['email'].toString().match(emailRegex)) {
                if (this.invalidMessage.length != 0) {
                    this.invalidMessage += " ";
                }
                this.invalidMessage += "Invalid email address.";
                this.invalid = true;
            }
        }
    },
    watch: {
        customer: function() {
            this.customerEdit = JSON.parse(JSON.stringify(this.customer));
        }
    }
}
</script>
