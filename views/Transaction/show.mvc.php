{% extends 'base.mvc.php' %}
{% block title %}Transactions{% endblock %}
{% block content %}
    <div>
        <a href="/transactions/insert">Create new Transaction</a>
        <div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check #</th>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    {% if (! empty($transactions)): %}
                    {% foreach ($transactions as $transaction): %}
                    <tr>
                        <td>{% formatDate($date) %}</td>
                        <td>{{ checkNumber }} </td>
                        <td>{{ description }}</td>
                        <td>
                            {% if (amount < 0): %}
                            <span style="color: red;">
                                {% formatDollarAmount($amount) %}
                            </span>
                            {% elseif (amount > 0): %}
                            <span style="color: green;">
                                {% formatDollarAmount($amount) %}
                            </span>
                            {% else: %}
                            {% formatDollarAmount($amount) %}
                            {% endif %}
                        </td>
                    </tr>
                    {% endforeach %}
                    {% endif %}
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total Income:</th>
                        <td>{%  formatDollarAmount($totalIncome ?? 0) %}</td>
                    </tr>
                    <tr>
                        <th colspan="3">Total Expense:</th>
                        <td>{% formatDollarAmount($totalExpense ?? 0) %}</td>
                    </tr>
                    <tr>
                        <th colspan="3">Net Total:</th>
                        <td>{% formatDollarAmount($netTotal ?? 0) %}</td>
                    </tr>
                </tfoot>
            </table>
            <a href="/transactions/{{ checkNumber }}/edit">Edit transaction</a>
            <a href="/transactions/{{ checkNumber }}/delete">Delete transaction</a>
        </div>
    </div>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        table tr th,
        table tr td {
            padding: 5px;
            border: 1px #eee solid;
        }

        tfoot tr th,
        tfoot tr td {
            font-size: 20px;
        }

        tfoot tr th {
            text-align: right;
        }
    </style>
{% endblock %}