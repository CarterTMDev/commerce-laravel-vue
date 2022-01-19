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
            error: false
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
            let valid = true;
            // Input is validated in form by browser defaults
            if (valid) {
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
                            valid = await this.updateOrder(newOrder, this.order.id);
                        }
                        if (valid) {
                            this.closeModal();
                        } else {
                            this.error = true;
                        }
                        break;
                    case "add":
                        valid = await this.addOrder(this.orderEdit);
                        if (valid) {
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
        }
    },
    watch: {
        order: function() {
            this.orderEdit = JSON.parse(JSON.stringify(this.order));
        }
    }
}
</script>
