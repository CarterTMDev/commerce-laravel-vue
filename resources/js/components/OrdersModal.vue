<template>
    <form @submit.prevent="onSubmit" @modal:mode="mode = $event">
    <div class="modal fade" :id="id" tabindex="-1" :aria-labelledby="id + 'Label'" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" :id="id + 'Label'">{{ mode === "add" ? "Add" : "Edit" }} Order</h5>
                    <button ref="modalCloseBtn" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <label for="initial_cost" class="form-label">Cost (before shipping)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input v-model="orderEdit.initial_cost" id="initial_cost" type="number" step="0.01" class="form-control" placeholder="" required>
                                </div>
                            </div>
                            <div class="col">
                                <label for="shipping_cost" class="form-label">Shipping Cost</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input v-model="orderEdit.shipping_cost" id="shipping_cost" type="number" step="0.01" class="form-control" placeholder="" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" v-model="orderEdit.isShipped" id="isShipped">
                            <label class="form-check-label" for="isShipped">
                                Shipped
                            </label>
                        </div>
                        <p v-if="error" class="text-danger">
                            An error occurred. Please try again.
                        </p>
                        <p v-if="invalid" class="text-danger">
                            {{ invalidMessage }}
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">{{ mode === "add" ? "Add order" : "Save changes" }}</button>
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
            orderEdit: JSON.parse(JSON.stringify(this.order)),
            mode: {
                type: String,
                default: 'add',
                validator: function(val) {
                    return ['add', 'edit'].indexOf(val) !== -1;
                }
            },
            error: false,
            invalid: false,
            invalidMessage: ""
        }
    },
    props: {
        order: Object,
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
                let success = true;
                switch (this.mode) {
                    case "edit":
                        // Store all changed order info in newOrder object
                        let newOrder = {};
                        for (const key in this.orderEdit) {
                            if (Object.hasOwnProperty.call(this.orderEdit, key)) {
                                const element = this.orderEdit[key];
                                if (element !== this.order[key]) {
                                    newOrder[key] = element;
                                }
                            }
                        }
                        // Don't bother the API if nothing changed
                        if (Object.keys(newOrder).length !== 0) {
                            success = await this.updateOrder(newOrder, this.order.id);
                        }
                        if (success) {
                            this.closeModal();
                        } else {
                            this.error = true;
                        }
                        break;
                    case "add":
                        success = await this.addOrder(this.orderEdit);
                        if (success) {
                            this.closeModal();
                        } else {
                            this.error = true;
                        }
                        break;
                }
            }
        },
        async updateOrder(updatedOrder, orderId) {
            let method = "PATCH";
            let request = {
                'method': method,
                body: JSON.stringify(updatedOrder),
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            return fetch(window.location.origin + '/api/orders/' + orderId, request)
                .then(res => {
                    if (res.ok) {
                        return res.json()
                                .then(res => {
                                    this.$emit("update:order", res);
                                    return true;
                                }, () => { return false });
                    } else {
                        return false;
                    }
                })
        },
        async addOrder(newOrder) {
            let request = {
                method: "POST",
                body: JSON.stringify(newOrder),
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            return fetch(window.location.origin + '/api/orders', request)
                    .then(res => {
                        if (res.ok) {
                            return res.json()
                                    .then(res => {
                                        this.$emit("add:order", res);
                                        return true;
                                    }, () => { return false });
                        } else {
                            return false;
                        }
                    })
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
            // id: limit to max BIGINT value
            if (Number(this.orderEdit['customer_id']) > 18446744073709551615) {
                // The user shouldn't be able to trigger this because the UI
                // doesn't let you manually set customer_id
                this.invalid = true;
                this.error = true;
            }
            // floats: 8 digits with 2 precision, limit to < 999999.99
            if (Number(this.orderEdit['initial_cost']) > 999999.99
                    || Number(this.orderEdit['shipping_cost']) > 999999.99) {
                this.invalid = true;
                this.invalidMessage = "Maximum amount for shipping or initial cost is $999,999.99";
            }
            // boolean
            if (this.orderEdit['isShipped'] !== true && this.orderEdit['isShipped'] !== false) {
                // Again, the user shouldn't be able to spoof a checkbox's value
                this.invalid = true;
                this.error = true;
            }
        }
    },
    watch: {
        order: function() {
            this.orderEdit = JSON.parse(JSON.stringify(this.order));
            if (this.orderEdit['isShipped'] === null) {
                this.orderEdit['isShipped'] = false;
            }
        }
    }
}
</script>
