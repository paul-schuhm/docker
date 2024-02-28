	.file	"main.c"
	.text
	.section	.rodata
	.align 8
.LC0:
	.string	"Enter your guess (between 0 and %d): "
.LC1:
	.string	"%d"
.LC2:
	.string	"Too high! Try again."
.LC3:
	.string	"Too low! Try again."
	.align 8
.LC4:
	.string	"Congratulations! You guessed the number"
	.text
	.globl	main
	.type	main, @function
main:
.LFB6:
	.cfi_startproc
	pushq	%rbp
	.cfi_def_cfa_offset 16
	.cfi_offset 6, -16
	movq	%rsp, %rbp
	.cfi_def_cfa_register 6
	subq	$32, %rsp
	movl	$100, -8(%rbp)
	movl	$10, -12(%rbp)
	movl	$0, -4(%rbp)
	movl	$0, %edi
	call	time@PLT
	movl	%eax, -16(%rbp)
	movl	-16(%rbp), %eax
	movl	%eax, %edi
	call	srand@PLT
	call	rand@PLT
	cltd
	idivl	-8(%rbp)
	movl	%edx, -20(%rbp)
.L6:
	movl	-8(%rbp), %eax
	movl	%eax, %esi
	leaq	.LC0(%rip), %rax
	movq	%rax, %rdi
	movl	$0, %eax
	call	printf@PLT
	leaq	-24(%rbp), %rax
	movq	%rax, %rsi
	leaq	.LC1(%rip), %rax
	movq	%rax, %rdi
	movl	$0, %eax
	call	__isoc99_scanf@PLT
	movl	-24(%rbp), %eax
	cmpl	%eax, -20(%rbp)
	jge	.L2
	leaq	.LC2(%rip), %rax
	movq	%rax, %rdi
	call	puts@PLT
	jmp	.L3
.L2:
	movl	-24(%rbp), %eax
	cmpl	%eax, -20(%rbp)
	jle	.L4
	leaq	.LC3(%rip), %rax
	movq	%rax, %rdi
	call	puts@PLT
	jmp	.L3
.L4:
	leaq	.LC4(%rip), %rax
	movq	%rax, %rdi
	call	puts@PLT
.L3:
	addl	$1, -4(%rbp)
	movl	-24(%rbp), %eax
	cmpl	%eax, -20(%rbp)
	je	.L5
	movl	-4(%rbp), %eax
	cmpl	-12(%rbp), %eax
	jl	.L6
.L5:
	movl	$0, %eax
	leave
	.cfi_def_cfa 7, 8
	ret
	.cfi_endproc
.LFE6:
	.size	main, .-main
	.ident	"GCC: (Debian 12.2.0-14) 12.2.0"
	.section	.note.GNU-stack,"",@progbits
