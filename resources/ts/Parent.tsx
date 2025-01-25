import React, { memo, useState } from "react"

type FizzProps = {
    isFizz: boolean
}

const Fizz = (props: FizzProps) => {
    const {isFizz} = props
    console.log(`Fizzが再描画されました,isFizz=${isFizz}`)
    return (
        <span>{isFizz ? 'FIzz' : ''}</span>
    )
}

type BazzProps = {
    isBuzz: boolean
}