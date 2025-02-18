import React, { useMemo, useState } from "react"

export const UseMempSample = () => {
    const [text, setText] = useState<string>('')
    const [items, setItems] = useState<string[]>([])

    const onChangeInput = (e: React.ChangeEvent<HTMLInputElement>) => {
        setText(e.target.value)
    }

    const onClickButton = () => {
        setItems((prevItem) => {
            return [...prevItem, text]
        })
        setText('')
    }

    const numberOfCharacters1 = items.reduce((sub, item) => sub + item.length, 0)
    const numberOfCharacters2 = useMemo(() => items.reduce((sub, item) => sub + item.length, 0), [items])

    return (
        <div>
            <p>UseMemoSample</p>
            <div>
                <input value={text} onChange={onChangeInput}></input>
                <button onClick={onClickButton}>Add</button>
            </div>
            <div>
                {
                    items.map((item, index) => <p key={index}>{item}</p>)
                }
            </div>
            <div>
                <p>Total Number of Characters1: {numberOfCharacters1}</p>
                <p>Total Number of Characters2: {numberOfCharacters2}</p>
            </div>
        </div>
    )
}





type ButtonProps = {
    onClick: () => void
}

const DecrementButton = (props: ButtonProps) => {
    const {onClick} = props
    console.log(`DecrementButtonが再描画されました`)

    return <button onClick={onClick}>Decrement</button>
}

const IncrementButton = React.memo((props: ButtonProps) => {
    const {onClick} = props
    console.log('IncrementButtonが再描画されました')
    return <button onClick={onClick}>Incremen</button>
})

const DoubleButton = React.memo((props: ButtonProps) => {
    const {onClick} = props
    console.log('DoubleButtonが再描画されました')
    return <button onClick={onClick}>Double</button>
})

export const Parent = () => {
    const [count, setCount] = useState(0)
    const decrement = () => {
        setCount((c) => c - 1)
    }
    const increment = () => {
        setCount((c) => c + 1)
    }
    const double = useCallback(() => {
        setCount((c) => c * 2)
    }, []);

    return (
        <div>
            <p>Count: {count}</p>
            <DecrementButton onClick={decrement}></DecrementButton>
            <IncrementButton onClick={increment}></IncrementButton>
            <DoubleButton onClick={double}></DoubleButton>
        </div>
    )
}