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
                                    <input v-model="orderEdit.initial_cost" id="initial_cost" type="number" class="form-control" placeholder="" required>
                                </div>
                            </div>
                            <div class="col">
                                <label for="shipping_cost" class="form-label">Shipping Cost</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input v-model="orderEdit.shipping_cost" id="shipping_cost" type="number" class="form-control" placeholder="" required>
                                </div>
                            </div>
                            <!-- TODO: Select items purchased -->
                            <!-- TODO: Restrict currency inputs to 2 decimal places and no leading zeros -->
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" v-model="orderEdit.isShipped" id="isShipped">
                            <label class="form-check-label" for="isShipped">
                                Shipped
                            </label>
                        </div>
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
            }
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
        onSubmit() {
            let valid = true;
            // TODO: Validate input
            if (valid) {
                let success = true;
                switch (this.mode) {
                    case "edit":
                        // TODO: Check if order info has been changed
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
                            // Fetch patch request to update order
                            this.updateOrder(newOrder, this.order.id);
                        }
                        // TODO: Handle failed requests
                        if (success) {
                            this.closeModal();
                        }
                        break;
                    case "add":
                        this.addOrder(this.orderEdit);
                        // TODO: Handle failed requests
                        if (success) {
                            this.closeModal();
                        }
                        break;
                }
            }
        },
        updateOrder(updatedOrder, orderId) {
            let method = "PATCH";
            let request = {
                'method': method,
                body: JSON.stringify(updatedOrder),
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            fetch(window.location.origin + '/api/orders/' + orderId, request)
                .then(res => res.json())
                .then(res => this.$emit("update:order", res))
                .catch(error => console.log(error));
        },
        addOrder(newOrder) {
            let request = {
                method: "POST",
                body: JSON.stringify(newOrder),
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            fetch(window.location.origin + '/api/orders', request)
                .then(res => res.json())
                .then(res => this.$emit("add:order", res))
                .catch(error => console.log(error));
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
