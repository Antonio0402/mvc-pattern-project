
    <label for="date">Date</label>
    <input type="date" id="date" name="date" required value="{{ $transaction['date'] }}">

    <label for="checkNumber">Check Number</label>
    <input type="text" id="checkNumber" name="checkNumber" value="{{ $transaction['checkNumber'] }}">

    <label for="description">Description</label>
    <input type="text" id="description" name="description" required value="{{ $transaction['description'] }}">

    <label for="amount">Amount</label>
    <input type="number" id="amount" name="amount" step="0.01" required value="{{ $transaction['amount'] }}">

    <button type="submit">{{ $transaction['btnText'] }} Transaction</button>
