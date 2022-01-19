<template>
<div class="pt-3">
    <h3>Orders for {{ customer.first_name + ' ' + customer.last_name }}</h3>
    <p>Click on an order to view its report</p>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Order placed</th>
                <th>Initial Price</th>
                <th>Shipping Cost</th>
                <th>Has Shipped</th>
            </tr>
        </thead>
        <tbody>
            <tr
                v-for="order in orders"
                :key="order.id"
                @click="selectOrder(order)"
            >
                <td>{{ timestampToDateTime(order.created_at) }}</td>
                <td>${{ Number(order.initial_cost).toFixed(2) }}</td>
                <td>${{ Number(order.shipping_cost).toFixed(2) }}</td>
                <td>{{ order.isShipped ? 'Yes' : 'No' }}</td>
                <td @click.stop>
                    <button
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#ordersModal"
                        @click="editOrder = order;
                                if (editOrder.shipping_cost !== null) {
                                    editOrder.shipping_cost = Number(editOrder.shipping_cost).toFixed(2);
                                }
                                if (editOrder.initial_cost !== null) {
                                    editOrder.initial_cost = Number(editOrder.initial_cost).toFixed(2);
                                }
                                $emit('modal:mode', 'edit');"
                    >
                        Edit
                    </button>
                </td>
                <td @click.stop>
                    <button
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#deleteModal"
                        @click="editOrder = order"
                    >
                        Delete
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
    <button
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#ordersModal"
        @click="editOrder = order;
                editOrder.customer_id = customerId;
                $emit('modal:mode', 'add');"
    >
        Add order
    </button>
    <orders-modal
        id="ordersModal"
        @update:order="updateOrder"
        @add:order="addOrder"
        :order="editOrder"
    >
    </orders-modal>
    <warning-modal
        id="deleteModal"
        label="Delete order"
        :message="'Are you sure you want to permanently delete this order?'"
        @deleteModal:success="deleteOrder"
    >
    </warning-modal>
</div>
</template>

<script>
import OrdersModal from './OrdersModal.vue';
import WarningModal from './WarningModal.vue';
export default {
    components: { OrdersModal, WarningModal },
    data() {
        return {
            customer: {},
            orders: [],
            editOrder: JSON.parse(JSON.stringify(this.order))
        }
    },
    props: {
        customerId: Number,
        order: Object
    },
    created() {
        // Get an updated version of the customer's data
        // (in case their data was changed between now and whenever
        // customer data was last retrieved)
        fetch(window.location.origin + '/api/customers/' + this.customerId)
            .then(res => res.json())
            .then(res => this.customer = res)
            .then(() => this.editOrder.customer_id = this.customerId)
            .then(
                fetch(window.location.origin + '/api/customers/' + this.customerId + '/orders')
                    .then(res => res.json())
                    .then(res => this.orders = res)
                    .catch(error => {
                        console.log(error);
                    })
            )
            .catch(error => {
                console.log(error);
            });
    },
    methods: {
        selectOrder(order) {
            window.location.href='/orders/' + order.id;
        },
        addOrder(order) {
            this.orders.push(order);
        },
        updateOrder(order) {
            this.editOrder = order;
            for (let i = 0; i < this.orders.length; i++) {
                if (this.orders[i].id === order.id) {
                    this.orders[i] = order;
                    break;
                }
            }
        },
        deleteOrder() {
            fetch(window.location.origin + '/api/orders/' + this.editOrder.id, {
                method: 'DELETE'
            })
            .then(res => res.ok)
            .then(success => {
                if (success) {
                    // Remove deleted order from orders
                    for (let i = 0; i < this.orders.length; i++) {
                        if (this.orders[i].id === this.editOrder.id) {
                            this.orders.splice(i, 1);
                            break;
                        }
                    }
                    // Set editOrder to the empty order object
                    this.editOrder = this.order;
                    this.editOrder.customer_id = this.customerId;
                } else {
                    alert("An error occurred. Please try again.");
                }
            });
        },
        timestampToDateTime(timestamp) {
            let date = new Date(timestamp);
            return date.toDateString();
        }
    }
}
</script>
