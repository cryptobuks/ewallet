<template>

<div>
    <div class="row">
        <div class="col-lg-3 col-xs-6 add-order-button">
            <router-link :to="{ name: 'add-new-order' }" class="btn btn-block btn-primary btn-sm">
                <i class="fa fa-fw fa-inbox "></i> Add new Order
            </router-link>
        </div>
        <div class="col-lg-3 col-xs-6 add-order-button">
            <router-link :to="{ name: 'orders' }" class="btn btn-block btn-primary btn-sm">
                <i class="fa fa-fw fa-inbox "></i> All Orders
            </router-link>
        </div>
        <div class="col-lg-3 col-xs-6 add-order-button">
            <router-link :to="{ name: 'categories' }" class="btn btn-block btn-primary btn-sm">
                <i class="fa fa-fw fa-list "></i> Add new Category
            </router-link>
        </div>
        <div class="col-lg-3 col-xs-6 add-order-button">
            <router-link :to="{ name: 'add-new-wallet' }" class="btn btn-block btn-primary btn-sm">
                <i class="fa fa-fw fa-money "></i> Add new Wallet
            </router-link>
        </div>
    </div>

    <div class="row">
        <AllOrders :numberOfOrders="allOrders.numberOfOrders" :all-orders-url="allOrders.allOrdersUrl"></AllOrders>
        <OrdersCurrentMonth :numberOfOrders="ordersCurrentMonth.numberOfOrders"></OrdersCurrentMonth>
        <SpentMoney :spent-money="spentMoney.spentMoney" :currency="spentMoney.currency"></SpentMoney>
        <SpentMoneyCurrentMonth :spent-money="spentMoneyCurrentMonth.spentMoney" :currency="spentMoneyCurrentMonth.currency"></SpentMoneyCurrentMonth>
    </div>

    <div class="row">
        <LatestOrders :orders="latestOrders.data" :currency="latestOrders.currency" :loading="latestOrders.loading"></LatestOrders>
        <SpentMoneyByMonth></SpentMoneyByMonth>
    </div>

</div>
</template>

<script>
import AllOrders from "./AllOrdersComponent";
import OrdersCurrentMonth from "./OrdersCurrentMonthComponent";
import SpentMoney from "./SpentMoneyComponent";
import LatestOrders from "./LatestOrdersComponent";
import SpentMoneyCurrentMonth from "./SpentMoneyCurrentMonthComponent";
import DashboardApi from '../Api/DashboardApi'
import SpentMoneyByMonth from './SpentMoneyByMonth'
import { getCall } from '../Api/utils/Endpoint';

export default {
    name: "DashboardComponent",
    components: {
        AllOrders,
        OrdersCurrentMonth,
        SpentMoney,
        LatestOrders,
        SpentMoneyCurrentMonth,
        SpentMoneyByMonth
    },
    data() {
        return {
            allOrders: {
                numberOfOrders: '<i class="fa fa-refresh fa-spin"></i>',
                allOrdersUrl: null
            },
            ordersCurrentMonth: {
                numberOfOrders: '<i class="fa fa-refresh fa-spin"></i>',
            },
            spentMoneyCurrentMonth: {
                spentMoney: '<i class="fa fa-refresh fa-spin"></i>',
                currency: this.$root.$data.currency
            },
            spentMoney: {
                spentMoney: '<i class="fa fa-refresh fa-spin"></i>',
                currency: this.$root.$data.currency
            },
            latestOrders: {
                loading: false,
                data: [],
                currency: this.$root.$data.currency
            }
        };
    },
    mounted() {
        this.getNumberOfOrdersCurrentMonth();
        this.getNumberOfOrders();
        this.getSpentMoneyCurrentMonth();
        this.getSpentMoney();
        this.getLatestOrders();
    },
    methods: {
        getNumberOfOrdersCurrentMonth() {
          getCall(this.$root.$data.apiUrl + DashboardApi.getNumberOfOrdersCurrentMonth,  {user: this.$root.$data.userId})
            .then(response => {
              this.ordersCurrentMonth.numberOfOrders =
                response.data;
            })
            .catch(error => {
              swal(error.response.data.message, "", "error");
            });
        },
        getNumberOfOrders() {
          getCall(this.$root.$data.apiUrl + DashboardApi.getNumberOfOrders, {user: this.$root.$data.userId})
                .then(response => {
                    this.allOrders.numberOfOrders =
                        response.data.numberOfOrders;
                    this.allOrders.allOrdersUrl = response.data.allOrdersUrl;
                })
                .catch(error => {
                    swal(error.response.data.message, "", "error");
                });
        },
        getSpentMoneyCurrentMonth() {
          getCall(this.$root.$data.apiUrl + DashboardApi.getSpentMoneyCurrentMonth, {user: this.$root.$data.userId})
                .then(response => {
                    this.spentMoneyCurrentMonth.spentMoney = response.data;
                })
                .catch(error => {
                    swal(error.response.data.message, "", "error");
                });
        },
        getSpentMoney() {
          getCall(this.$root.$data.apiUrl + DashboardApi.getSpentMoney, {user: this.$root.$data.userId})
                .then(response => {
                    this.spentMoney.spentMoney = response.data;
                })
                .catch(error => {
                    swal(error.response.data.message, "", "error");
                });
        },
        getLatestOrders() {
          this.latestOrders.loading = true;
          getCall(this.$root.$data.apiUrl + DashboardApi.getLatestOrders, {user: this.$root.$data.userId})
                .then(response => {
                    this.latestOrders.loading = false;
                    this.latestOrders.data = response.data;
                })
                .catch(error => {
                    this.latestOrders.loading = false;
                    swal(error.response.data.message, "", "error");
                });
        }
    }
};
</script>

<style scoped>
.add-order-button {
    margin-bottom: 15px;
}
</style>
