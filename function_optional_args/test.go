package main

import "fmt"

func my_append(a_list []string, new_item string) ([]string, []string) {
	a_list = append(a_list, new_item)
	return a_list, a_list
}

func main() {
	var (
		x      []string
		y      []string
		a_list []string
	)

	x, a_list = my_append(a_list, "One")
	fmt.Printf("%v\n", x)

	y, a_list = my_append(a_list, "Two")
	fmt.Printf("%v\n", y)

}
