<template>
<div class="pt-3">
    <h3>Order Report</h3>
    <table class="table table-striped">
        <tbody>
            <tr v-for="(item, name) in report" :key="name">
                <th>{{ name }}</th>
                <td>{{ item }}</td>
            </tr>
        </tbody>
    </table>
    <button
        class="btn btn-primary"
        @click="exportToCsv"
    >
    Export to .CSV
    </button>
</div>
</template>

<script>
export default {
    data() {
        return {
            report: {},
            order: {},
            customer: {}
        }
    },
    props: {
        orderId: Number
    },
    created() {
        // Get the order data
        fetch(window.location.origin + '/api/orders/' + this.orderId)
            .then(res => res.json())
            .then(res => {
                this.order = res;
                return res;
            })
            .then(orderRes => {
                // Get the customer data
                fetch(window.location.origin + '/api/customers/' + orderRes.customer_id)
                    .then(res => res.json())
                    .then(res => {
                        this.customer = res;
                        // Generate the report object from the order and customer data
                        this.createReport()
                    })
                    .catch(error => {
                        console.log(error);
                    })
            })
            .catch(error => {
                console.log(error);
            });
    },
    methods: {
        getFullAddress(customer) {
            let address = customer.address_1;
            address += customer.address_2 ? " " + customer.address_2 + ", " : ", ";
            address += customer.city + ", ";
            address += customer.state + " " + customer.zipcode;
            return address;
        },
        createReport() {
            this.report = {
                'Order Id': this.order.id,
                'Name': this.customer.first_name + ' ' + this.customer.last_name,
                'Address': this.getFullAddress(this.customer),
                'Total': '$' + Number(this.order.initial_cost + this.order.shipping_cost).toFixed(2)
            };
        },
        exportToCsv() {
            
        }
    }
}
</script>
