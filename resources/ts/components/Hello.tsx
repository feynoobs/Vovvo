import React, {memo} from 'react'

const Fizz = (props: {isFizz: boolean}): JSX.Element => {
    console.log(`Fizzが再描画されました。isFizz: ${props.isFizz}`)

    return <p>{props.isFizz ? 'Fizz' : ''}</p>
}

const Buzz = memo<{isBuzz: boolean, onClick: () => void}>((props: {isBuzz: boolean, onClick: () => void}): JSX.Element => {
    console.log(`Buzzが再描画されました。isBuzz: ${props.isBuzz}`)

    return <p onClick={props.onClick}>{props.isBuzz ? 'Buzz' : ''}</p>
})

const Hello = (prpps: {}): JSX.Element => {
    const [count, setCount] = React.useState(1)
    const isFizz = count % 3 === 0
    const isBuzz = count % 5 === 0
    const onBuzzClick = () => {    
        console.log(`Buzzがクリックされました。isBuzz=${isBuzz}`)
    }
    console.log(`Helloが再描画されました。count: ${count}`)

    return (
        <div>
            <p>カウント: {count}</p>
            <button onClick={() => setCount(count + 1)}>カウントアップ</button>
            <Fizz isFizz={isFizz} />
            <Buzz isBuzz={isBuzz} onClick={onBuzzClick} />
        </div>
    )
}

export default Hello