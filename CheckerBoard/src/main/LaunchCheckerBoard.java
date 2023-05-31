package main;

public class LaunchCheckerBoard {

    public static void main(String[] args) {
        javax.swing.SwingUtilities.invokeLater(() -> {
            try {
                System.out.println("Hello World!!!");
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }//end main
}
