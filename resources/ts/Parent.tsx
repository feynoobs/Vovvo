import React, { useCallback, useState } from "react"

type ButtonProps = {
    onClick: () => void
}

const DecrementButton = (props: ButtonProps) => {
    const {onClick} = props
    console.log(`DecrementButtonが再描画されました`)

    return <button onClick={onClick}>Decremen</button>
}

const IncrementButton = React.memo((props: ButtonProps) => {
    const {onClick} = props
    console.log('IncrementButtonが再描画されました')
    return <button onClick={onClick}>Decremen</button>
})

const DoubleButton = React.memo((props: ButtonProps) => {
    const {onClick} = props
    console.log('DoubleButtonが再描画されました')
    return <button onClick={onClick}>Decremen</button>
})

export const Parent = () => {
    const [count, setCount] = useState(0)
}