#!/usr/bin/env node
import chalk from "chalk";

const name = process.argv[2] || "Docker";
console.log(chalk.yellow(`Bonjour, ${name} !`));