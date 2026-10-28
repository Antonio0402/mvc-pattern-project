{% extends 'base.mvc.php' %}
    {% blocks title %}Delete Transaction{% endblock %}
{% block content %}
    <h1>Are you sure deleting this Transaction</h1>
    <form action="/transactions/{{ $id }}/destroy" method="post">
        <button type="button">Delete</button>
    </form>
    <a href="/transaction/{{ $slug }}">Cancel</a>
{% endblock %}