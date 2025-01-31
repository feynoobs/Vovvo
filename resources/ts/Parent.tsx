import React, { useCallback, useState, memo } from "react"

type ButtonProps = {
    onClick: () => void
}

const DecrimentButton = memo((props: ButtonProps) => {
    const {onClick} = props
    console.log('DecrimentButtonが再描画されました')
    return (
        <button onClick={onClick}>Decriment</button>
    )
})

const IncrimentButton = memo((props: ButtonProps) => {
    const {onClick} = props
    console.log('IncrimentButtonが再描画されました')
    return (
        <button onClick={onClick}>Incriment</button>
    )
})

const DoubleButton = memo((props: ButtonProps) => {
    const {onClick} = props
    console.log('DoubleButtonが再描画されました')
    return (
        <button onClick={onClick}>Double</button>
    )
})


export const Parent = () => {
    const [count, setCount] = useState(0)
    const descriment = () => {setCount((c) => c - 1)}
    const inscriment = () => {setCount((c) => c + 1)}
    const double = useCallback(() => {setCount((c) => c * 2)}, [])

    return (
        <div>
            <p>Count:{count}</p>
            <DecrimentButton onClick={descriment}></DecrimentButton>
            <IncrimentButton onClick={inscriment}></IncrimentButton>
            <DoubleButton onClick={double}></DoubleButton>
        </div>
    )
}