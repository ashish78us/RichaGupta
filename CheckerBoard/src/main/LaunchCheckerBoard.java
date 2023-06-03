package main;

import view.JeuMainView;

public class LaunchCheckerBoard {

    public static void main(String[] args) {
        javax.swing.SwingUtilities.invokeLater(() -> {
            try {
                System.out.println("Hello World!!!");
                JeuMainView.getJeuMainView().initializeView();
            } catch (Exception e) {
                e.printStackTrace();
            }
        });
    }//end main
}
