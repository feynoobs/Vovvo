import React, { useReducer, JSX, useState } from "react"
type Action='DECREMENT'|'INCREMENT'|'DOUBLE'|'RESET'

const reducer = (currentCount: number, action: Action) => {
    switch (action) {
        case 'INCREMENT':
            return currentCount + 1;
        case 'DECREMENT':
            return currentCount - 1
        case 'DOUBLE':
            return currentCount * 2
        case 'RESET':
            return 0
        default:
            return currentCount
    }
}
type CounterProps = {
    initalValue: number
}

const Counter = (props: CounterProps) => {
    const { initalValue } = props
    const [count, dispatch] = useReducer(reducer, initalValue)

    return (
        <div>
            <p>{count}</p>
            <button onClick={() => (dispatch('DECREMENT'))}>-</button>
            <button onClick={() => (dispatch('INCREMENT'))}>+</button>
            <button onClick={() => (dispatch('DOUBLE'))}>x2</button>
            <button onClick={() => (dispatch('RESET'))}>Reset</button>
        </div>
    )
}

export default Counter

